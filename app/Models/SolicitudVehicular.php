<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVehicular extends Model
{
    use HasFactory;
    //*Nombre de la tabla que "evalúa el modelo" */
    protected $table = 'solicitudes_vehiculos';
    //*Definimos la llave primaria */
    //? Debe corresponder con la definida en la migración
    protected $primaryKey = 'SOLICITUD_VEHICULO_ID';
    //*Definimos los campos que podrán ser modificados */
    //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
    protected $fillable = [
        'USUARIO_id',
        'VEHICULO_ID',
        'RENDICION_ID',
        'SOLICITUD_VEHICULO_MOTIVO',
        'SOLICITUD_VEHICULO_ESTADO',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICIADA',
        'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

        //!!Esto sera para vehiculos una vez listo
        /*public function categoria()
        {
            return $this->belongsTo(CategoriaReparacion::class, 'CATEGORIA_REPARACION_ID', 'CATEGORIA_REPARACION_ID');
        }*/

        //!!Esto sera para rendicion una vez listo
        /*public function categoria()
        {
            return $this->belongsTo(CategoriaReparacion::class, 'CATEGORIA_REPARACION_ID', 'CATEGORIA_REPARACION_ID');
        }*/
}
