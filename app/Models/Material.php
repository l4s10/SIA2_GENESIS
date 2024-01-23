<?php

namespace App\Models;

use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model implements Buyable
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'materiales';
    // Llave primaria
    protected $primaryKey = 'MATERIAL_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'MATERIAL_NOMBRE',
        'MATERIAL_STOCK',
        'OFICINA_ID',
        'TIPO_MATERIAL_ID'
    ];

    //Relación uno a uno con la tabla tipos_materiales
    public function tipoMaterial()
    {
        return $this->belongsTo(TipoMaterial::class, 'TIPO_MATERIAL_ID');
    }

    //Relacion de uno a uno con Oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
    //cart relationships

    public function getBuyableIdentifier($options = null)
    {
        return $this->getKey();
    }
    
    public function getBuyableDescription($options = null)
    {
        return $this->MATERIAL_NOMBRE;
    }

    public function getBuyablePrice($options = null)
    {
        // Puedes ajustar esta lógica según tus necesidades
        return $this->MATERIAL_STOCK;
    }
}
