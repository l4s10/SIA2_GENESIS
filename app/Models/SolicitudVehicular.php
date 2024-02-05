<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVehicular extends Model
{
    use HasFactory;
    protected $table = 'solicitudes_vehiculos';
    protected $primaryKey = 'SOLICITUD_VEHICULO_ID';

    protected $fillable = [
        'USUARIO_id',
        'TIPO_VEHICULO_ID',
        'VEHICULO_ID',
        //'RENDICION_ID',
        'SOLICITUD_VEHICULO_COMUNA_ORIGEN',
        'SOLICITUD_VEHICULO_COMUNA_DESTINO',
        'SOLICITUD_VEHICULO_MOTIVO',
        'SOLICITUD_VEHICULO_ESTADO',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICIADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA'
    ];

    public function viajan()
    {
        return $this->belongsToMany(User::class, 'viajan', 'SOLICITUD_VEHICULO_ID', 'USUARIO_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    public function vehiculo()
    {
        return $this->hasOne(Vehiculo::class, 'VEHICULO_ID', 'VEHICULO_ID');
    }


    public function comunaDestino()
    {
        return $this->belongsTo(Comuna::class, 'SOLICITUD_VEHICULO_COMUNA_DESTINO', 'COMUNA_ID');
    }
}
