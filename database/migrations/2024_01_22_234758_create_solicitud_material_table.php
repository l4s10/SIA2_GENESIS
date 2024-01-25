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
        Schema::create('solicitudes_materiales', function (Blueprint $table) {
            $table->unsignedBigInteger('SOLICITUD_ID');
            $table->unsignedBigInteger('MATERIAL_ID');
            $table->integer('SOLICITUD_MATERIAL_CANTIDAD');
            $table->integer('SOLICITUD_MATERIAL_CANTIDAD_AUTORIZADA')->nullable();

            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
            $table->foreign('MATERIAL_ID')->references('MATERIAL_ID')->on('materiales');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_materiales');
    }
};
