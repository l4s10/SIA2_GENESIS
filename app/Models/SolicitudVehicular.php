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
        'VEHICULO_ID',
        'COMUNA_ID',
        'CONDUCTOR_id',
        'SOLICITUD_VEHICULO_MOTIVO',
        'SOLICITUD_VEHICULO_ESTADO',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA',
        'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION',
        'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION',
        'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA',
        'SOLICITUD_VEHICULO_VIATICO',
    ];

    protected $dates = [
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA',
    ];


    public function pasajeros()
    {
        return $this->hasMany(Pasajero::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'CONDUCTOR_id', 'id');
    }


    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'VEHICULO_ID', 'VEHICULO_ID');
    }
    
    // Relación con la orden de trabajo (0:1)
    public function ordenTrabajo()
    {
        return $this->hasOne(OrdenDeTrabajo::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }

    public function comunaDestino()
    {
        return $this->belongsTo(Comuna::class, 'COMUNA_ID', 'COMUNA_ID');
    }


}



