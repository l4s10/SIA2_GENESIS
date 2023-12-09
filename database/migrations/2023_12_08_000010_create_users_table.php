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
        Schema::create('users', function (Blueprint $table) {
            //!! Campos plantilla Laravel (NO CAMBIAR)
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            //* Campos correcciones (CATEGORIZACIONES)*/
            $table->unsignedBigInteger('OFICINA_ID'); //!! EQUIVALENTE A DIRECCIÓN_REGIONAL.
            $table->unsignedBigInteger('DEPARTAMENTO_ID')->nullable();
            $table->unsignedBigInteger('UBICACION_ID')->nullable();
            $table->unsignedBigInteger('GRUPO_ID')->nullable();
            $table->unsignedBigInteger('ESCALAFON_ID');
            $table->unsignedBigInteger('GRADO_ID');
            $table->unsignedBigInteger('CARGO_ID');
            //* Campos correcciones (DATOS PERSONALES) */
            $table->string('USUARIO_RUT')->unique();
            $table->date('USUARIO_FECHA_NAC');
            $table->date('USUARIO_FECHA_INGRESO');
            $table->string('USUARIO_FONO');
            $table->string('USUARIO_ANEXO');
            $table->string('USUARIO_CALIDAD_JURIDICA');
            $table->string('USUARIO_SEXO');
            $table->string('USUARIO_NOMBRES');
            $table->string('USUARIO_APELLIDOS');
            //!! Campos por defecto de Laravel
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            //!! LLaves foráneas
            $table->foreign('OFICINA_ID')->references('OFICINA_ID')->on('oficinas');
            $table->foreign('DEPARTAMENTO_ID')->references('DEPARTAMENTO_ID')->on('departamentos');
            $table->foreign('UBICACION_ID')->references('UBICACION_ID')->on('ubicaciones');
            $table->foreign('GRUPO_ID')->references('GRUPO_ID')->on('grupos');
            $table->foreign('ESCALAFON_ID')->references('ESCALAFON_ID')->on('escalafones');
            $table->foreign('GRADO_ID')->references('GRADO_ID')->on('grados');
            $table->foreign('CARGO_ID')->references('CARGO_ID')->on('cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
