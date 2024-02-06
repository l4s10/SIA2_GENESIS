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
        Schema::create('polizas', function (Blueprint $table) {
            $table->id('POLIZA_ID');
            $table->unsignedBigInteger('USUARIO_id');
            $table->unsignedBigInteger('OFICINA_ID');
            $table->date('POLIZA_FECHA_VENCIMIENTO_LICENCIA');
            $table->integer('POLIZA_NUMERO');
            
            // Constraints
            $table->foreign('USUARIO_id')->references('id')->on('users');
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            
            // Unique keys
            //$table->unique(['OFICINA_ID', 'POLIZA_NUMERO']); // Clave única compuesta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};