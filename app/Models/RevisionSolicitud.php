<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionSolicitud extends Model
{

    protected $table = 'revisiones_solicitudes';

    protected $primaryKey = 'REVISION_SOLICITUD_ID';

    protected $fillable = [
        'USUARIO_id',
        'SOLICITUD_REPARACION_ID',
        'SOLICITUD_VEHICULO_ID',
        'SOLICITUD_ID',
        'REVISION_SOLICITUD_OBSERVACION',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    public function solicitudReparacion()
    {
        return $this->belongsTo(SolicitudReparacion::class, 'SOLICITUD_REPARACION_ID', 'SOLICITUD_REPARACION_ID');
    }

    public function solicitudVehiculo()
    {
        return $this->belongsTo(SolicitudVehiculo::class, 'SOLICITUD_VEHICULO_ID', 'SOLICITUD_VEHICULO_ID');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
    }
}
