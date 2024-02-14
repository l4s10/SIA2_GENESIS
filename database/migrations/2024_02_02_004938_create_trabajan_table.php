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
        Schema::create('trabajan', function (Blueprint $table) {
            $table->id('TRABAJA_ID');
            $table->unsignedBigInteger('SOLICITUD_VEHICULO_ID');
            $table->unsignedBigInteger('USUARIO_id');
            $table->integer('TRABAJA_NUMERO_ORDEN_TRABAJO');
            $table->time('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
            $table->time('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');
    
            $table->foreign('SOLICITUD_VEHICULO_ID')->references('SOLICITUD_VEHICULO_ID')->on('solicitudes_vehiculos');
            $table->foreign('USUARIO_id')->references('id')->on('users');

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajan');
    }
};
