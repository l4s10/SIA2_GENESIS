<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'facultades';
    protected $primaryKey = 'FACULTAD_ID';
    protected $fillable = [
        'FACULTAD_NUMERO',
        'FACULTAD_NOMBRE',
        'FACULTAD_CONTENIDO',
        'FACULTAD_LEY_ASOCIADA',
        'FACULTAD_ART_LEY_ASOCIADA',
    ];

    // Relación muchos a muchos con Resolucion a través de la tabla intermedia
    public function resoluciones()
    {
        return $this->belongsToMany(Resolucion::class, 'delegan_facultades', 'FACULTAD_ID', 'RESOLUCION_ID');
    }

}
