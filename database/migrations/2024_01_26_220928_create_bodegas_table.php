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
        Schema::create('bodegas', function (Blueprint $table) {
            $table->id('BODEGA_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('BODEGA_NOMBRE', 128);
            $table->string('BODEGA_ESTADO', 40);

            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            $table->unique(['OFICINA_ID', 'BODEGA_NOMBRE']); // Clave única compuesta
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('bodegas');
    }
};
