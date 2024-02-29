<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudSala extends Model
{
    protected $table = 'solicitudes_salas';

    protected $fillable = ['SOLICITUD_ID', 'SALA_ID', 'SOLICITUD_SALA_ID_ASIGNADA'];

    public $timestamps = true;

    // Relacion de uno a uno con Sala
    public function salaAsignada()
    {
        return $this->belongsTo(Sala::class, 'SOLICITUD_SALA_ID_ASIGNADA', 'SALA_ID');
    }

}

