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
        Schema::create('solicitudes_bodegas', function (Blueprint $table) {
            $table->id('SOLICITUD_ID');
            $table->unsignedBigInteger('BODEGA_ID');
            $table->integer('SOLICITUD_BODEGA_ID_ASINGADA')->nullable();
            $table->timestamps();

            //foraneas
            $table->foreign('SOLICITUD_ID')->references('SOLICITUD_ID')->on('solicitudes');
            $table->foreign('BODEGA_ID')->references('BODEGA_ID')->on('bodegas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_bodegas');
    }
};
