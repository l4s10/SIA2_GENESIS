<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;
        //*Nombre de la tabla que "evalúa el modelo" */
        protected $table = 'solicitudes';
        //*Definimos la llave primaria */
        //? Debe corresponder con la definida en la migración
        protected $primaryKey = 'SOLICITUD_ID';
        //*Definimos los campos que podrán ser modificados */
        //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
        protected $fillable = [
            'USUARIO_id',
            'UBICACION_NOMBRE',
            'SOLICITUD_MOTIVO',
            'SOLICITUD_ESTADO',
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'
        ];

        //**Relaciones */
        /*
            Relacion de uno a uno -> BelongsTo
            Relacion de uno a muchos / cero a muchos -> HasMany
        */
        //*Relación de uno a uno con User*/
        public function solicitud()
        {
            return $this->belongsTo(User::class, 'USUARIO_id', 'id');
        }
        // Relación de muchos a muchos con Material
        public function materiales()
        {
            return $this->belongsToMany(Material::class, 'solicitud_material', 'SOLICITUD_ID', 'MATERIAL_ID')
                ->withPivot('cantidad') // Si necesitas acceder a la cantidad desde la relación
                ->withTimestamps(); // Si necesitas las marcas de tiempo
        }
}
