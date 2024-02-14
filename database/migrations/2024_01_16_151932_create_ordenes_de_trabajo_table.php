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
        Schema::create('ordenes_de_trabajo', function (Blueprint $table) {
            $table->id('ORDEN_TRABAJO_ID');
            $table->integer('ORDEN_TRABAJO_NUMERO')->unsigned()->unique();
            $table->time('ORDEN_TRABAJO_HORA_INICIO');
            $table->time('ORDEN_TRABAJO_HORA_TERMINO');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};
