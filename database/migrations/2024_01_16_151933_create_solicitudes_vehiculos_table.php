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
            $table->unsignedBigInteger('COMUNA_ID');
            $table->unsignedBigInteger('VEHICULO_ID');
            // $table->unsignedBigInteger('RENDICION_ID');
            $table->string('SOLICITUD_VEHICULO_MOTIVO', 255);
            $table->string('SOLICITUD_VEHICULO_ESTADO', 30);
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA');
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA')->nullable();
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA');
            $table->dateTime('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA')->nullable();
            $table->time('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION');
            $table->time('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION');
            $table->string('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA',128);
            $table->string('SOLICITUD_VEHICULO_VIATICO',4);


            //*Llaves foráneas*/
            $table->foreign('USUARIO_id')->references('id')->on('users');
            $table->foreign('VEHICULO_ID')->references('VEHICULO_ID')->on('vehiculos');
            // $table->foreign('RENDICION_ID')->references('RENDICION_ID')->on('rendiciones');
            $table->foreign('COMUNA_ID')->references('COMUNA_ID')->on('comunas');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_vehiculos');
    }
};
