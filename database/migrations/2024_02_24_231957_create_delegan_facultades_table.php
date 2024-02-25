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
        Schema::create('delegan_facultades', function (Blueprint $table) {
            $table->unsignedBigInteger('RESOLUCION_ID');
            $table->unsignedBigInteger('FACULTAD_ID');
            
            // Definición de claves foráneas
            $table->foreign('RESOLUCION_ID')->references('RESOLUCION_ID')->on('resoluciones')->onDelete('cascade');
            $table->foreign('FACULTAD_ID')->references('FACULTAD_ID')->on('facultades')->onDelete('cascade');

            // Clave primaria compuesta
            $table->primary(['RESOLUCION_ID', 'FACULTAD_ID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegan_facultades');
    }
};
