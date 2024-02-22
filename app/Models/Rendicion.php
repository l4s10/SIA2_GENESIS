<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendicion extends Model
{
    use HasFactory;

    protected $table = 'rendiciones'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'RENDICION_ID'; // Nombre de la clave primaria en la tabla

    protected $fillable = [
        'USUARIO_id',
        'SOLICITUD_VEHICULO_ID',
        'RENDICION_NUMERO_BITACORA',
        'RENDICION_FECHA_HORA_LLEGADA',
        'RENDICION_KILOMETRAJE_INICIO',
        'RENDICION_KILOMETRAJE_TERMINO',
        'RENDICION_NIVEL_ESTANQUE',
        'RENDICION_ABASTECIMIENTO',
        'RENDICION_TOTAL_HORAS',
        'RENDICION_OBSERVACIONES',
    ];

    // Define la relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    // Define la relación con la solicitud vehicular
    public function solicitudVehicular()
    {
        return $this->belongsTo(SolicitudVehicular::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULAR_ID');
    }
}
