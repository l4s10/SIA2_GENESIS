<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'tipos_materiales';
    // Llave primaria
    protected $primaryKey = 'TIPO_MATERIAL_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'TIPO_MATERIAL_NOMBRE',
        'OFICINA_ID'
    ];
    // Relación uno a muchos con materiales
    public function materiales()
    {
        return $this->hasMany(Material::class, 'TIPO_MATERIAL_ID');
    }
    // Relación uno a uno con oficina
    public function oficinas()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
