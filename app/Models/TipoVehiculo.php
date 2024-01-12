<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'tipos_vehiculos';
    // Llave primaria
    protected $primaryKey = 'TIPO_VEHICULO_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'TIPO_VEHICULO_NOMBRE',
        'OFICINA_ID'
    ];
    // Relación uno a muchos con vehiculos
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'TIPO_VEHICULO_ID');
    }
    // Relación uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}
