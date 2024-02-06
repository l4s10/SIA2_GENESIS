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
        Schema::create('grados', function (Blueprint $table) {
            //*Atributos de la tabla Grados */
            $table->id('GRADO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->integer('GRADO_NUMERO');
            $table->timestamps();
            
            //*Llaves foráneas */
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            // Clave única compuesta
            $table->unique(['OFICINA_ID', 'GRADO_NUMERO']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
