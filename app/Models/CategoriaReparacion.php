<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaReparacion extends Model
{
    use HasFactory;
    //*Nombre de la tabla que "evalúa el modelo" */
    protected $table = 'categorias_reparaciones';
    //*Definimos la llave primaria */
    //? Debe corresponder con la definida en la migración
    protected $primaryKey = 'CATEGORIA_REPARACION_ID';
    //*Definimos los campos que podrán ser modificados */
    //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
    protected $fillable = [
        'CATEGORIA_REPARACION_NOMBRE'
    ];

    // Relacion de uno a muchos con la tabla de solicitud de reparaciones
    public function solicitudes()
    {
        return $this->hasMany(SolicitudReparacion::class, 'CATEGORIA_REPARACION_ID');
    }
}
