<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escalafones', function (Blueprint $table) {
            //*Atributos de la tabla Escalafones */
            $table->id('ESCALAFON_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('ESCALAFON_NOMBRE', 128);
            $table->timestamps();
            //*Llaves foráneas*/
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            // Clave única compuesta
            $table->unique(['OFICINA_ID', 'ESCALAFON_NOMBRE']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalafones');
    }
};
