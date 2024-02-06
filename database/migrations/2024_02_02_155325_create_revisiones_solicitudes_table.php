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
        Schema::create('revisiones_solicitudes', function (Blueprint $table) {
            $table->id('REVISION_SOLICITUD_ID');
            $table->unsignedBigInteger('USUARIO_ID');
            $table->unsignedBigInteger('SOLICITUD_REPARACION_ID')->nullable();
            $table->unsignedBigInteger('SOLICITUD_VEHICULO_ID')->nullable();
            $table->unsignedBigInteger('SOLICITUD_ID')->nullable();
            $table->string('REVISION_SOLICITUD_OBSERVACION', 255)->nullable();

            //foraneas
            $table->foreign('USUARIO_ID')->references('id')->on('users');
            $table->foreign('SOLICITUD_REPARACION_ID')->references('SOLICITUD_REPARACION_ID')->on('solicitudes_reparaciones');
            $table->foreign('SOLICITUD_VEHICULO_ID')->references('SOLICITUD_VEHICULO_ID')->on('solicitudes_vehiculos');
            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisiones_solicitudes');
    }
};
