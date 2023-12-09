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
        Schema::create('oficinas', function (Blueprint $table) {
            //*Propiedades de oficina (equivalente a DIRECCION REGIONAL) */
            $table->id('OFICINA_ID');
            $table->unsignedBigInteger('COMUNA_ID');
            $table->string('OFICINA_NOMBRE', 128)->unique();
            $table->timestamps();

            //*LLaves foráneas */
            $table->foreign('COMUNA_ID')->references('COMUNA_ID')->on('comunas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oficinas');
    }
};
