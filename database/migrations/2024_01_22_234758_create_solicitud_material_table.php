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
        Schema::create('solicitud_material', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('SOLICITUD_ID');
            $table->unsignedBigInteger('MATERIAL_ID');
            $table->integer('cantidad'); // Agrega cualquier otro atributo que necesites

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
        Schema::dropIfExists('solicitud_material');
    }
};
