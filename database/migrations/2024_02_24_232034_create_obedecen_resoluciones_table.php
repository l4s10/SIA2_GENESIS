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
        Schema::create('obedecen_resoluciones', function (Blueprint $table) {
            $table->unsignedBigInteger('RESOLUCION_ID');
            $table->unsignedBigInteger('CARGO_ID');
            
            $table->timestamps();


            // Definición de claves foráneas
            $table->foreign('RESOLUCION_ID')->references('RESOLUCION_ID')->on('resoluciones')->onDelete('CASCADE');
            $table->foreign('CARGO_ID')->references('CARGO_ID')->on('cargos')->onDelete('CASCADE');

            // Clave primaria compuesta
            $table->primary(['RESOLUCION_ID', 'CARGO_ID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obedecen_resoluciones');
    }
};
