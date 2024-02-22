<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    protected $table = 'pasajeros';
    protected $primaryKey = ['USUARIO_id', 'SOLICITUD_VEHICULO_ID'];
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'USUARIO_id',
        // otros atributos aquí
    ];
    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }
   
    // Relación con el modelo SolicitudVehiculo
    public function solicitudVehicular()
    {
        return $this->belongsTo(SolicitudVehicular::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }

}