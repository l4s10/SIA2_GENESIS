<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionSolicitud extends Model
{
    protected $table = 'revisiones_solicitudes';

    protected $primaryKey = 'REVISION_SOLICITUD_ID';

    protected $fillable = [
        'USUARIO_ID',
        'SOLICITUD_ID',
        'REVISION_SOLICITUD_FECHA_HORA_TRAMITACION', 'REVISION_SOLICITUD_OBSERVACION'
    ];

    public $timestamps = false;


    public function usuario()
    {
        return $this->belongsTo(User::class, 'USUARIO_ID', 'id');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
    }

    //!!PROBAR!! (PARA FINES DE PRUEBA, NO SE USA EN EL PROYECTO POR AHORA)
    public function obtenerFechaTramitacion($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }
    // Como llamo a esta funcion en la vista?
    // $revision->obtenerFechaTramitacion($revision->REVISION_SOLICITUD_FECHA_HORA_TRAMITACION)

}
