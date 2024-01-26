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
        Schema::create('solicitudes_salas_o_bodegas', function (Blueprint $table) {
            $table->unsignedBigInteger('SOLICITUD_ID');
            $table->unsignedBigInteger('SALA_O_BODEGA_ID');
            // SALA O BODEGA ID -> ASIGNADA? y nullable
            //Foraneas
            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
            $table->foreign('SALA_O_BODEGA_ID')->references('SALA_O_BODEGA_ID')->on('salas_o_bodegas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_salas_o_bodegas');
    }
};
