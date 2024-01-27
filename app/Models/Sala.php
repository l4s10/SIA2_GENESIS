<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'salas';
    // Llave primaria
    protected $primaryKey = 'SALA_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'SALA_NOMBRE',
        'SALA_CAPACIDAD',
        'SALA_ESTADO',
        'OFICINA_ID'
    ];
    // Relacion uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
