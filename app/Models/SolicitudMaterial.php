<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudMaterial extends Model
{
    protected $table = 'solicitudes_materiales';

    protected $fillable = ['SOLICITUD_ID', 'MATERIAL_ID', 'SOLICITUD_MATERIAL_CANTIDAD', 'SOLICITUD_MATERIAL_CANTIDAD_AUTORIZADA'];

    public $timestamps = true;


    // Funcion para traer todas las solicitudes que contenengan un material
    public function solicitudes()
    {
        return $this->belongsTo(Solicitud::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
    }

}
