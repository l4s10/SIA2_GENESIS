<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;

    //*Nombre de la tabla que "evalúa el modelo" */
    protected $table = 'comunas';
    //*Definimos la llave primaria */
    //? Debe corresponder con la definida en la migración
    protected $primaryKey = 'COMUNA_ID';
    //*Definimos los campos que podrán ser modificados */
    //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
    protected $fillable = [
        'REGION_ID',
        'COMUNA_NOMBRE',
    ];
    //*Definición de relaciones*/
    /*
        Relacion de uno a uno -> BelongsTo
        Relacion de uno a muchos / cero a muchos -> HasMany
    */
    //* Relación de uno y solo uno con Region*/
    public function region()
    {
        return $this->belongsTo(Region::class, 'REGION_ID', 'REGION_ID');
    }
}
