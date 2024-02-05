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
        Schema::create('solicitudes', function (Blueprint $table) {
            //*Atributos de solicitudes */
            $table->id('SOLICITUD_ID');
            $table->unsignedBigInteger('USUARIO_id');
            $table->string('SOLICITUD_MOTIVO', 255);
            $table->string('SOLICITUD_ESTADO', 30);
            $table->dateTime('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA');
            $table->dateTime('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA');
            $table->dateTime('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA')->nullable();
            $table->dateTime('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA')->nullable();
            //*Llaves foráneas*/
            $table->foreign('USUARIO_ID')->references('id')->on('users');
            //Timestamps (Fecha de creacion y de modificacion del registro)
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
