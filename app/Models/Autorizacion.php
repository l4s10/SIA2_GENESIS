<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    protected $table = 'autorizaciones';
    protected $primaryKey = ['USUARIO_id', 'SOLICITUD_VEHICULO_ID'];
    public $incrementing = false;
    public $timestamps = true;

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }


    // Relación con el modelo SolicitudVehiculo
    public function solicitudVehiculo()
    {
        return $this->belongsTo(SolicitudVehicular::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }
}