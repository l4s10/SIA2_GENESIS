<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudSala extends Model
{
    protected $table = 'solicitudes_salas';

    protected $fillable = ['SOLICITUD_ID', 'SALA_ID'];

    public $timestamps = true;
}
