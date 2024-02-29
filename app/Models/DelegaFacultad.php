<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DelegaFacultad extends Model
{
    use HasFactory;
    protected $table = 'delegan_facultades';
    protected $primaryKey = ['RESOLUCION_ID','FACULTAD_ID'];
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'FACULTAD_ID',
    ];

    // Relación con el modelo Resolucion
    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'RESOLUCION_ID', 'RESOLUCION_ID');
    }
    
    // Relación con el modelo Facultad
    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'FACULTAD_ID', 'FACULTAD_ID');
    }
}