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
        Schema::create('solicitudes_salas', function (Blueprint $table) {
            $table->id('SOLICITUD_ID');
            $table->unsignedBigInteger('SALA_ID');
            $table->integer('SOLICITUD_SALA_ID_ASIGNADA')->nullable();
            $table->timestamps();

            // Foraneas
            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
            $table->foreign('SALA_ID')->references('SALA_ID')->on('salas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_salas');
    }
};
