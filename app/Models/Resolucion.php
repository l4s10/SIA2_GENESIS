<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    use HasFactory;

    protected $table = 'resoluciones';

    protected $primaryKey = 'RESOLUCION_ID';

    protected $fillable = [
        'TIPO_RESOLUCION_ID',
        'CARGO_ID',
        'RESOLUCION_NUMERO',
        'RESOLUCION_FECHA',
        'RESOLUCION_DOCUMENTO',
        'RESOLUCION_OBSERVACIONES',
    ];

    public function tipoResolucion()
    {
        return $this->belongsTo(TipoResolucion::class, 'TIPO_RESOLUCION_ID');
    }

    // Relación 1:1 con Cargo (una resolución es firmada por un cargo)
    public function firmante()
    {
        return $this->belongsTo(Cargo::class, 'CARGO_ID');
    }

    public function obedientes()
    {
        return $this->hasMany(ObedeceResolucion::class, 'RESOLUCION_ID', 'RESOLUCION_ID');
    }

    public function delegacion()
    {
        return $this->hasMany(DelegaFacultad::class, 'RESOLUCION_ID', 'RESOLUCION_ID');
    }

}