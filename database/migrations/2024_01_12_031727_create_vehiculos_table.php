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
        Schema::create('vehiculos', function (Blueprint $table) {
            //Atributos
            $table->id('VEHICULO_ID');
            $table->unsignedBigInteger('TIPO_VEHICULO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('VEHICULO_PATENTE', 7)->unique();
            $table->string('VEHICULO_MARCA', 128);
            $table->string('VEHICULO_MODELO', 191);
            $table->string('VEHICULO_ANO', 10);
            $table->string('VEHICULO_ESTADO', 20);
            $table->integer('VEHICULO_KILOMETRAJE');
            $table->string('VEHICULO_NIVEL_ESTANQUE', 128);
            //Relaciones
            $table->foreign('TIPO_VEHICULO_ID')->references('TIPO_VEHICULO_ID')->on('tipos_vehiculos');
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
        Schema::dropIfExists('vehiculos');
    }
};
