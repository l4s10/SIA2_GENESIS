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
        Schema::create('cargos', function (Blueprint $table) {
            //*Atributos de la tabla Cargos */
            $table->id('CARGO_ID');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->string('CARGO_NOMBRE', 128);
            $table->timestamps();
            //*Llaves foráneas*/
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
