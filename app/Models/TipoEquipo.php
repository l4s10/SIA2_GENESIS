<?php

namespace App\Models;

use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model implements Buyable
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

    // Metodos para el carrito de compras
    public function getBuyableIdentifier($options = null)
    {
        return $this->getKey();
    }

    public function getBuyableDescription($options = null)
    {
        return $this->TIPO_EQUIPO_NOMBRE;
    }

    public function getBuyablePrice($options = null)
    {
        // El tipo de equipo al no tener un stock como tal, se le asigna un precio de 0.
        return 0;
    }
}
