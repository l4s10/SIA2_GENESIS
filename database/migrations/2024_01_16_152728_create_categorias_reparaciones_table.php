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
        Schema::create('categorias_reparaciones', function (Blueprint $table) {
            $table->id('CATEGORIA_REPARACION_ID');
            $table->string('CATEGORIA_REPARACION_NOMBRE',60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_reparaciones');
    }
};
