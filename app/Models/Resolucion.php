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
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'CARGO_ID');
    }

    // Relación n:n con Facultad a través de la tabla intermedia
    public function facultades()
    {
        return $this->belongsToMany(Facultad::class, 'delegan_facultades', 'RESOLUCION_ID', 'FACULTAD_ID');
    }

    // Relación n:n con Cargo a través de la tabla intermedia 'obedecen_resoluciones'
    public function obediencia()
    {
        return $this->belongsToMany(Cargo::class, 'obedecen_resoluciones', 'RESOLUCION_ID', 'CARGO_ID');
    }
}