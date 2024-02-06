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
        Schema::create('grupos', function (Blueprint $table) {
            //*Atributos de la tabla Grupos */
            $table->id('GRUPO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('GRUPO_NOMBRE', 128);
            $table->timestamps();
            //*Llaves foráneas*/
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');

            // Clave única compuesta
            $table->unique(['OFICINA_ID', 'GRUPO_NOMBRE']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
