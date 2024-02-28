<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    //*Nombre de la tabla que "evalúa el modelo" */
    protected $table = 'cargos';
    //*Definimos la llave primaria */
    //? Debe corresponder con la definida en la migración
    protected $primaryKey = 'CARGO_ID';
    //*Definimos los campos que podrán ser modificados */
    //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
    protected $fillable = [
        'OFICINA_ID',
        'CARGO_NOMBRE',
    ];

    //**Relaciones */
    /*
        Relacion de uno a uno -> BelongsTo
        Relacion de uno a muchos / cero a muchos -> HasMany
    */
    // Relación 1:n con Resolucion (un cargo obedece a múltiples resoluciones)
    public function firma()
    {
        return $this->hasMany(Resolucion::class, 'CARGO_ID');
    }


    // Relación 1:1 con Oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID', 'OFICINA_ID');
    }
}
