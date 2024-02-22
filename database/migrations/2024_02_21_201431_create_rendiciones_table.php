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
        Schema::create('rendiciones', function (Blueprint $table) {
            $table->id('RENDICION_ID');
            $table->unsignedBigInteger('USUARIO_id');
            $table->unsignedBigInteger('SOLICITUD_VEHICULO_ID');
            $table->integer('RENDICION_NUMERO_BITACORA')->unique();
            $table->timestamp('RENDICION_FECHA_HORA_LLEGADA');
            $table->integer('RENDICION_KILOMETRAJE_INICIO');
            $table->integer('RENDICION_KILOMETRAJE_TERMINO');
            $table->string('RENDICION_NIVEL_ESTANQUE', 15);
            $table->string('RENDICION_ABASTECIMIENTO', 4);
            $table->integer('RENDICION_TOTAL_HORAS');
            $table->string('RENDICION_OBSERVACIONES', 255)->nullable();

            $table->timestamps();

            $table->foreign('USUARIO_id')->references('id')->on('users'); // Si se elimina el usuario, se establece a NULL
            $table->foreign('SOLICITUD_VEHICULO_ID')->references('SOLICITUD_VEHICULO_ID')->on('solicitudes_vehiculos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendiciones');
    }
};
