<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SolicitudEquipos extends Model
{
    protected $table = 'solicitudes_equipos';

    protected $fillable = ['SOLICITUD_ID', 'TIPO_EQUIPO_ID', 'SOLICITUD_EQUIPOS_CANTIDAD', 'SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA'];

    public $timestamps = true;
}
