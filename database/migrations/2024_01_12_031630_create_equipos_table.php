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
        Schema::create('equipos', function (Blueprint $table) {
            //Atributos
            $table->id('EQUIPO_ID');
            $table->unsignedBigInteger('TIPO_EQUIPO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('EQUIPO_MARCA', 128);
            $table->string('EQUIPO_MODELO', 128);
            $table->string('EQUIPO_ESTADO', 40);
            $table->integer('EQUIPO_STOCK');
            //Relaciones
            $table->foreign('TIPO_EQUIPO_ID')->references('TIPO_EQUIPO_ID')->on('tipos_equipos');
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            // Clave única compuesta
            $table->unique(['OFICINA_ID', 'EQUIPO_MARCA', 'EQUIPO_MODELO' ]); 
            //Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
