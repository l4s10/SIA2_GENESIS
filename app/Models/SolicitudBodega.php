<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudSala extends Model
{
    protected $table = 'solicitudes_bodegas';

    protected $fillable = ['SOLICITUD_ID', 'BODEGA_ID'];

    public $timestamps = true;
}
