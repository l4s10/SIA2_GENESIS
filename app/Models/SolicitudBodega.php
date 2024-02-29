<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudBodega extends Model
{
    protected $table = 'solicitudes_bodegas';

    protected $fillable = ['SOLICITUD_ID', 'BODEGA_ID'];

    public $timestamps = true;

    // Relacion para devolver la solicitud asociada a la bodega
    public function solicitud()
    {
        return $this->belongsTo('App\Models\Solicitud', 'SOLICITUD_ID');
    }

    // Relacion para devolver la bodega autorizada a la solicitud
    public function bodega()
    {
        return $this->belongsTo('App\Models\Bodega', 'BODEGA_ID');
    }
}
