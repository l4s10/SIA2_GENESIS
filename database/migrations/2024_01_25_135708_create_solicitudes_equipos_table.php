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
        Schema::create('solicitudes_equipos', function (Blueprint $table) {
            $table->id('SOLICITUD_ID');
            $table->unsignedBigInteger('TIPO_EQUIPO_ID');
            $table->integer('SOLICITUD_EQUIPOS_CANTIDAD');
            $table->integer('SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA')->nullable();

            //Foraneas
            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
            $table->foreign('TIPO_EQUIPO_ID')->references('TIPO_EQUIPO_ID')->on('tipos_equipos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_equipos');
    }
};
