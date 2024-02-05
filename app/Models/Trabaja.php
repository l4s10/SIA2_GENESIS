<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajan extends Model
{
    use HasFactory;

    protected $table = 'trabajan';

    protected $primaryKey = 'TRABAJA_ID'; // Definimos TRABAJA_ID como la clave primaria

    protected $fillable = [
        'SOLICITUD_VEHICULO_ID',
        'USUARIO_id',
        'TRABAJA_NUMERO_ORDEN_TRABAJO',
        'TRABAJA_HORA_INICIO_ORDEN_TRABAJO',
        'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO',
        'TRABAJA_HORA_INICIO_CONDUCCION',
        'TRABAJA_HORA_TERMINO_CONDUCCION',
        'TRABAJA_VIATICO',
    ];

    public function solicitudVehicular()
    {
        return $this->belongsTo(SolicitudVehicular::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

}
