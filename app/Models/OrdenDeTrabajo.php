<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDeTrabajo extends Model
{
    protected $table = 'ordenes_de_trabajo';

    protected $primaryKey = 'ORDEN_TRABAJO_ID';

    protected $fillable = [
        'SOLICITUD_VEHICULO_ID',
        'ORDEN_TRABAJO_NUMERO',
        'ORDEN_TRABAJO_HORA_INICIO',
        'ORDEN_TRABAJO_HORA_TERMINO',
    ];

    // Relación inversa con la solicitud vehicular (1:1)
    public function solicitudVehicular()
    {
        return $this->belongsTo(SolicitudVehicular::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }

    
}
