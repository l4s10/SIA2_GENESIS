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
        Schema::create('pasajeros', function (Blueprint $table) {
            $table->unsignedBigInteger('USUARIO_id');
            $table->unsignedBigInteger('SOLICITUD_VEHICULO_ID');
            
            // Definición de las claves foráneas
            $table->foreign('USUARIO_id')->references('id')->on('users');
            $table->foreign('SOLICITUD_VEHICULO_ID')->references('SOLICITUD_VEHICULO_ID')->on('solicitudes_vehiculos');

            // Timestamps
            $table->timestamps();

            // Definición de la clave primaria compuesta
            $table->primary(['USUARIO_id', 'SOLICITUD_VEHICULO_ID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasajeros');
    }
};
