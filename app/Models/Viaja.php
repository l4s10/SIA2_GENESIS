<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaja extends Model
{
    protected $table = 'viajan';
    protected $primaryKey = ['USUARIO_id', 'SOLICITUD_VEHICULO_ID'];
    public $incrementing = false;
    public $timestamps = true;

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id');
    }

    // Relación con el modelo SolicitudVehiculo
    public function solicitudVehiculo()
    {
        return $this->belongsTo(SolicitudVehiculo::class, 'SOLICITUD_VEHICULO_ID');
    }
}