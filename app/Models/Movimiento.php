<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'movimientos';

    protected $fillable = [
        'USUARIO_id',
        'MATERIAL_ID',
        'EQUIPO_ID',
        'MOVIMIENTO_TITULAR',
        'MOVIMIENTO_OBJETO',
        'MOVIMIENTO_TIPO_OBJETO',
        'MOVIMIENTO_TIPO',
        'MOVIMIENTO_STOCK_PREVIO',
        'MOVIMIENTO_CANTIDAD_A_MODIFICAR',
        'MOVIMIENTO_STOCK_RESULTANTE',
        'MOVIMIENTO_DETALLE',
    ];


    // Relación 'equipos' 1:n 'movimientos'
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'EQUIPO_ID');
    }

    // Relación 'materiales' 1:n 'movimientos'
    public function material()
    {
        return $this->belongsTo(Material::class, 'MATERIAL_ID');
    }

    // Relación 'users' 1:n 'movimientos'
    public function usuario()
    {
        return $this->belongsTo(User::class, 'USUARIO_id');
    }

}