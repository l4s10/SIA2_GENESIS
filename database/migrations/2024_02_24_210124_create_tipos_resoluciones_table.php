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
        Schema::create('tipos_resoluciones', function (Blueprint $table) {
            $table->id('TIPO_RESOLUCION_ID');
            $table->string('TIPO_RESOLUCION_NOMBRE', 128)->unique();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_resoluciones');
    }
};
