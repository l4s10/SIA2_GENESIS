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
        Schema::create('salas_o_bodegas', function (Blueprint $table) {
            //Atributos
            $table->id('SALA_O_BODEGA_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('SALA_O_BODEGA_NOMBRE', 128);
            $table->integer('SALA_O_BODEGA_CAPACIDAD')->nullable();
            $table->string('SALA_O_BODEGA_ESTADO', 40);
            $table->string('SALA_O_BODEGA_TIPO', 20);
            //Relaciones
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            //Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas_o_bodegas');
    }
};
