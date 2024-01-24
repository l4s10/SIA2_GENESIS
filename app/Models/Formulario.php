<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Formulario extends Model implements Buyable
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

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'FORMULARIO_ID');
    }

    //** cart relationships
    public function getBuyableIdentifier($options = null)
    {
        return $this->getKey();
    }

    public function getBuyableDescription($options = null)
    {
        return $this->FORMULARIO_NOMBRE;
    }

    public function getBuyablePrice($options = null)
    {
        return 0;
    }
}
