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
        Schema::create('departamentos', function (Blueprint $table) {
            //*Atributos tabla departamentos */
            $table->id('DEPARTAMENTO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('DEPARTAMENTO_NOMBRE', 128);
            $table->timestamps();
            //** LLaves foráneas
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
