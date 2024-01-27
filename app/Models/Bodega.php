<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'bodegas';
    // Llave primaria
    protected $primaryKey = 'BODEGA_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'BODEGA_NOMBRE',
        'BODEGA_ESTADO',
        'OFICINA_ID'
    ];
    // Relacion uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
