<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->id('SALA_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('SALA_NOMBRE', 128);
            $table->integer('SALA_CAPACIDAD');
            $table->string('SALA_ESTADO', 40);

            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            $table->timestamps();

            $table->unique(['OFICINA_ID', 'SALA_NOMBRE']); // Clave única compuesta

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('salas');
    }
};
