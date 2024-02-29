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
        Schema::create('facultades', function (Blueprint $table) {
            $table->id('FACULTAD_ID');
            $table->unsignedBigInteger('FACULTAD_NUMERO');
            $table->text('FACULTAD_NOMBRE');
            $table->text('FACULTAD_CONTENIDO');
            $table->string('FACULTAD_LEY_ASOCIADA', 128);
            $table->string('FACULTAD_ART_LEY_ASOCIADA', 128);

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facultades');
    }
};
