<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            // Atributos
            $table->id('MOVIMIENTO_ID');
            $table->unsignedBigInteger('USUARIO_id')->nullable();
            $table->string('MOVIMIENTO_TITULAR', 255); // Nuevo campo para nombres y apellidos del usuario
            $table->string('MOVIMIENTO_OBJETO', 100); // Nuevo campo para nombre del objeto gestionado
            $table->string('MOVIMIENTO_TIPO_OBJETO', 100); // Nuevo campo para nombre del tipo del objeto gestionado
            $table->unsignedBigInteger('MATERIAL_ID')->nullable();
            $table->unsignedBigInteger('EQUIPO_ID')->nullable();
            $table->string('MOVIMIENTO_TIPO', 20);
            $table->unsignedInteger('MOVIMIENTO_STOCK_PREVIO');
            $table->unsignedInteger('MOVIMIENTO_CANTIDAD_A_MODIFICAR');
            $table->unsignedInteger('MOVIMIENTO_STOCK_RESULTANTE');
            $table->text('MOVIMIENTO_DETALLE');
            $table->timestamps();

            // Relaciones
            $table->foreign('USUARIO_id')->references('id')->on('users')->onDelete('set null'); // Si se elimina el usuario, se establece a NULL
            $table->foreign('MATERIAL_ID')->references('MATERIAL_ID')->on('materiales')->onDelete('set null'); // Si se elimina el material, se establece a NULL
            $table->foreign('EQUIPO_ID')->references('EQUIPO_ID')->on('equipos')->onDelete('set null'); // Si se elimina el equipo, se establece a NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
};
