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
        Schema::create('solicitudes_vehiculos', function (Blueprint $table) {
            //*Atributos de solicitudes */
            $table->id('SOLICITUD_VEHICULO_ID');
            $table->unsignedBigInteger('USUARIO_id');
            $table->integer('VEHICULO_ID');
            $table->integer('RENDICION_ID');
            $table->string('SOLICITUD_VEHICULO_MOTIVO', 255);
            $table->string('SOLICITUD_VEHICULO_ESTADO', 128);
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA');
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA')->nullable();
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICIADA');
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA')->nullable();
            //*Llaves foráneas*/
            $table->foreign('USUARIO_id')->references('id')->on('users');
            $table->foreign('VEHICULO_ID')->references('VEHICULO_ID')->on('vehiculos');
            $table->foreign('RENDICION_ID')->references('RENDICION_ID')->on('rendiciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_vehiculares');
    }
};
