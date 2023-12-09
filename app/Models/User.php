<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'OFICINA_ID',
        'DEPARTAMENTO_ID',
        'UBICACION_ID',
        'GRUPO_ID',
        'ESCALAFON_ID',
        'GRADO_ID',
        'CARGO_ID',
        // Datos personales
        'USUARIO_RUT',
        'USUARIO_FECHA_NAC',
        'USUARIO_FECHA_INGRESO',
        'USUARIO_FONO',
        'USUARIO_ANEXO',
        'USUARIO_CALIDAD_JURIDICA',
        'USUARIO_SEXO',
        'USUARIO_NOMBRES',
        'USUARIO_APELLIDOS',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
