<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'equipos';
    // Llave primaria
    protected $primaryKey = 'EQUIPO_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable =[
        'EQUIPO_MARCA',
        'EQUIPO_MODELO',
        'EQUIPO_ESTADO',
        'EQUIPO_STOCK',
        'TIPO_EQUIPO_ID',
        'OFICINA_ID'
    ];
    // Relación uno a uno con tipo_equipo
    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'TIPO_EQUIPO_ID');
    }
    // Relación uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
