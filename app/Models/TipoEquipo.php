<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'tipos_equipos';
    // Llave primaria
    protected $primaryKey = 'TIPO_EQUIPO_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'TIPO_EQUIPO_NOMBRE',
        'OFICINA_ID'
    ];
    // Relación uno a muchos con equipos
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'TIPO_EQUIPO_ID');
    }
    // Relación uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
