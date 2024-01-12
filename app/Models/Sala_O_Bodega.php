<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala_O_Bodega extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'salas_o_bodegas';
    // Llave primaria
    protected $primaryKey = 'SALA_O_BODEGA_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'SALA_O_BODEGA_NOMBRE',
        'SALA_O_BODEGA_CAPACIDAD',
        'SALA_O_BODEGA_ESTADO',
        'SALA_O_BODEGA_TIPO',
        'OFICINA_ID'
    ];
    // Relacion uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
