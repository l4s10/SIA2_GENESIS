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
        Schema::create('tipos_equipos', function (Blueprint $table) {
            //Atributos
            $table->id('TIPO_EQUIPO_ID');
            $table->string('TIPO_EQUIPO_NOMBRE', 128);
            $table->unsignedBigInteger('OFICINA_ID');
            //Relaciones
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            //Timestamps (Fecha de creacion y de modificacion del registro)
            $table->timestamps();

            // Clave única compuesta
            $table->unique(['OFICINA_ID', 'TIPO_EQUIPO_NOMBRE']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_equipos');
    }
};
