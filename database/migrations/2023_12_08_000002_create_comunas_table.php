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
        Schema::create('comunas', function (Blueprint $table) {
            //*Atributos tabla comunas */
            $table->id('COMUNA_ID');
            $table->unsignedBigInteger('REGION_ID');
            $table->string('COMUNA_NOMBRE', 40)->unique();
            $table->timestamps();
            //*LLaves foráneas */
            $table->foreign('REGION_ID')->references('REGION_ID')->on('regiones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunas');
    }
};
