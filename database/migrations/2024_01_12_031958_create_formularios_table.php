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
        Schema::create('formularios', function (Blueprint $table) {
            //Atributos
            $table->id('FORMULARIO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('FORMULARIO_NOMBRE', 128);
            $table->string('FORMULARIO_TIPO', 128);
            //Relaciones
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');

            // Regla de unicidad compuesta
            $table->unique(['OFICINA_ID', 'FORMULARIO_NOMBRE', 'FORMULARIO_TIPO']); 

            //Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formularios');
    }
};
