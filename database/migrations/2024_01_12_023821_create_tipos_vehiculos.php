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
        Schema::create('tipos_vehiculos', function (Blueprint $table) {
            //Atributos
            $table->id('TIPO_VEHICULO_ID');
            $table->string('TIPO_VEHICULO_NOMBRE', 128);
            $table->integer('TIPO_VEHICULO_CAPACIDAD');
            //Timestamps (Fecha de creacion y de modificacion del registro)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_vehiculos');
    }
};
