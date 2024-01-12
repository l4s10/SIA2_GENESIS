<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'vehiculos';
    // Llave primaria
    protected $primaryKey = 'VEHICULO_ID';
    // Declaramos el fillable para poder usar el método create() de Eloquent
    protected $fillable = [
        'VEHICULO_PATENTE',
        'VEHICULO_MARCA',
        'VEHICULO_MODELO',
        'VEHICULO_ANO',
        'VEHICULO_ESTADO',
        'VEHICULO_KILOMETRAJE',
        'VEHICULO_NIVEL_ESTANQUE',
        'TIPO_VEHICULO_ID',
        'UBICACION_ID',
        'DEPARTAMENTO_ID'
    ];
    // Relación uno a uno con tipo_vehiculo
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'TIPO_VEHICULO_ID');
    }
    // Relación uno a uno con ubicacion
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'UBICACION_ID')->withDefault();
    }
    // Relación uno a uno con departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'DEPARTAMENTO_ID')->withDefault();
    }
}
