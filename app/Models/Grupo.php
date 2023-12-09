<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    //*Nombre de la tabla que "evalúa el modelo" */
    protected $table = 'grupos';
    //*Definimos la llave primaria */
    //? Debe corresponder con la definida en la migración
    protected $primaryKey = 'GRUPO_ID';
    //*Definimos los campos que podrán ser modificados */
    //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
    protected $fillable = [
        'OFICINA_ID',
        'GRUPO_NOMBRE',
    ];

    //*Definición de relaciones*/
    /*
        Relacion de uno a uno -> BelongsTo
        Relacion de uno a muchos / cero a muchos -> HasMany
    */
    //* Relación de uno y solo uno con Oficina*/
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID', 'OFICINA_ID');
    }
}
