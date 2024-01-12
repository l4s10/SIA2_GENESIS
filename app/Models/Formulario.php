<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'formularios';
    // Llave primaria
    protected $primaryKey = 'FORMULARIO_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'FORMULARIO_NOMBRE',
        'FORMULARIO_TIPO',
        'OFICINA_ID'
    ];
    // Relación uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
    // Relacion de 0 a n con Solicitudes
}
