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
        Schema::create('materiales', function (Blueprint $table) {
            //Atributos
            $table->id('MATERIAL_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->unsignedBigInteger('TIPO_MATERIAL_ID');
            $table->string('MATERIAL_NOMBRE', 128);
            $table->integer('MATERIAL_STOCK');
            //Relaciones
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            $table->foreign('TIPO_MATERIAL_ID')->references('TIPO_MATERIAL_ID')->on('tipos_materiales');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
