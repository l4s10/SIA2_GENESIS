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
        Schema::create('resoluciones', function (Blueprint $table) {
            $table->id('RESOLUCION_ID');
            $table->unsignedBigInteger('TIPO_RESOLUCION_ID');
            $table->unsignedBigInteger('CARGO_ID')->nullable();
            $table->integer('RESOLUCION_NUMERO');
            $table->date('RESOLUCION_FECHA');
            $table->string('RESOLUCION_DOCUMENTO', 191)->nullable();
            $table->string('RESOLUCION_OBSERVACIONES', 255)->nullable();

            $table->foreign('TIPO_RESOLUCION_ID')->references('TIPO_RESOLUCION_ID')->on('tipos_resoluciones');
            $table->foreign('CARGO_ID')->references('CARGO_ID')->on('cargos');

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resoluciones');
    }
};
