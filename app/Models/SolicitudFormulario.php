<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudFormulario extends Model
{
    protected $table = 'solicitudes_formularios';

    protected $fillable = ['SOLICITUD_ID', 'FORMULARIO_ID', 'SOLICITUD_FORMULARIOS_CANTIDAD'];

    public $timestamps = true;
}
