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
            $table->unsignedBigInteger('UBICACION_ID')->nullable();
            $table->unsignedBigInteger('DEPARTAMENTO_ID')->nullable();
            $table->string('VEHICULO_PATENTE', 7)->unique();
            $table->string('VEHICULO_MARCA', 128);
            $table->string('VEHICULO_MODELO', 191);
            $table->string('VEHICULO_ANO', 10);
            $table->string('VEHICULO_ESTADO', 20);
            $table->integer('VEHICULO_KILOMETRAJE')->default(0);
            $table->string('VEHICULO_NIVEL_ESTANQUE', 128)->default('VACIO');
            //Relaciones
            $table->foreign('TIPO_VEHICULO_ID')->references('TIPO_VEHICULO_ID')->on('tipos_vehiculos');
            $table->foreign('DEPARTAMENTO_ID')->references('DEPARTAMENTO_ID')->on('departamentos');
            $table->foreign('UBICACION_ID')->references('UBICACION_ID')->on('ubicaciones');
            //Timestamps
            $table->timestamps();

            // Clave de unicidad compuesta: TIPO_VEHICULO_ID, UBICACION_ID, VEHICULO_PATENTE, VEHICULO_MARCA, VEHICULO_MODELO
            $table->unique(['TIPO_VEHICULO_ID', 'UBICACION_ID', 'VEHICULO_PATENTE', 'VEHICULO_MARCA', 'VEHICULO_MODELO'], 'unicidad_ubicacion_vehiculo');

            // Clave de unicidad compuesta: TIPO_VEHICULO_ID, DEPARTAMENTO_ID, VEHICULO_PATENTE, VEHICULO_MARCA, VEHICULO_MODELO
            $table->unique(['TIPO_VEHICULO_ID', 'DEPARTAMENTO_ID', 'VEHICULO_PATENTE', 'VEHICULO_MARCA', 'VEHICULO_MODELO'], 'unicidad_departamento_vehiculo');
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
