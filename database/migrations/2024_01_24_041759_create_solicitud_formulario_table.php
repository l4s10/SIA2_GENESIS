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
        Schema::create('solicitud_formulario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('SOLICITUD_ID');
            $table->unsignedBigInteger('FORMULARIO_ID');
            $table->integer('CANTIDAD'); // Agrega cualquier otro atributo que necesites

            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
            $table->foreign('FORMULARIO_ID')->references('FORMULARIO_ID')->on('formularios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_formulario');
    }
};
