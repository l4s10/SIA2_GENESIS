<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Solicitud;
use App\Models\Sala_O_Bodega;

class SolicitudSalaOBodega extends Model
{
    protected $table = 'solicitudes_salas_o_bodegas';


    protected $fillable = [
        'SOLICITUD_ID',
        'SALA_O_BODEGA_ID',
    ];

    public $timestamps = true;

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
    }

    public function salaOBodega()
    {
        return $this->belongsTo(Sala_O_Bodega::class, 'SALA_O_BODEGA_ID', 'SALA_O_BODEGA_ID');
    }
}
