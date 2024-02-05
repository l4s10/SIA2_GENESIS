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
        Schema::create('solicitudes_reparaciones', function (Blueprint $table) {
            //*Atributos de solicitudes */
            $table->id('SOLICITUD_REPARACION_ID');
            $table->unsignedBigInteger('USUARIO_id');
            $table->unsignedBigInteger('CATEGORIA_REPARACION_ID');
            $table->unsignedBigInteger('VEHICULO_ID')->nullable();
            $table->string('SOLICITUD_REPARACION_TIPO', 20);
            $table->string('SOLICITUD_REPARACION_MOTIVO', 255);
            $table->string('SOLICITUD_REPARACION_ESTADO', 30);
            $table->dateTime('SOLICITUD_REPARACION_FECHA_HORA_INICIO')->nullable();
            $table->dateTime('SOLICITUD_REPARACION_FECHA_HORA_TERMINO')->nullable();
            //*Llaves foráneas*/
            $table->foreign('USUARIO_id')->references('id')->on('users');
            $table->foreign('CATEGORIA_REPARACION_ID')->references('CATEGORIA_REPARACION_ID')->on('categorias_reparaciones');
            $table->foreign('VEHICULO_ID')->references('VEHICULO_ID')->on('vehiculos');
            //Timestamps (Fecha de creacion y de modificacion del registro)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_reparaciones');
    }
};
