<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudSala extends Model
{
    protected $table = 'solicitudes_salas';

    protected $fillable = ['SOLICITUD_ID', 'SALA_ID', 'SOLICITUD_SALA_ID_ASIGNADA'];

    public $timestamps = true;

    // Relacion para devolver la solicitud asociada a la sala
    public function solicitud()
    {
        return $this->belongsTo('App\Models\Solicitud', 'SOLICITUD_ID');
    }

    // Relacion para devolver la sala solicitada
    public function sala()
    {
        return $this->belongsTo('App\Models\Sala', 'SALA_ID');
    }

    // Relacion para devolver la sala asignada
    public function salaAsignada()
    {
        return $this->belongsTo('App\Models\Sala', 'SOLICITUD_SALA_ID_ASIGNADA');
    }
}


