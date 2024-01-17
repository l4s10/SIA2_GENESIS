<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudReparacion extends Model
{
    use HasFactory;
    //*Nombre de la tabla que "evalúa el modelo" */
    protected $table = 'solicitudes_reparaciones';
    //*Definimos la llave primaria */
    //? Debe corresponder con la definida en la migración
    protected $primaryKey = 'SOLICITUD_REPARACION_ID';
    //*Definimos los campos que podrán ser modificados */
    //!! Recomendación, todos los atributos (MENOS EL ID) deben ir aquí.
    protected $fillable = [
        'USUARIO_id',
        'UBICACION_NOMBRE',
        'SOLICITUD_REPARACION_TIPO',
        'CATEGORIA_REPARACION_ID',
        'SOLICITUD_REPARACION_MOTIVO',
        'SOLICITUD_REPARACION_ESTADO',
        'VEHICULO_ID',
        'SOLICITUD_REPARACION_FECHA_HORA_INICIO',
        'SOLICITUD_REPARACION_FECHA_HORA_TERMINO'
    ];

    //**Relaciones */
    /*
        Relacion de uno a uno -> BelongsTo
        Relacion de uno a muchos / cero a muchos -> HasMany
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaReparacion::class, 'CATEGORIA_REPARACION_ID', 'CATEGORIA_REPARACION_ID');
    }

    //!! ESTA BASE ESTA HECHA PARA VEHICULOS UNA VEZ LISTO uwu
    /*public function vehiculo()
    {
        return $this->belongsTo(Oficina::class, 'USUARIO_id', 'USUARIO_ID');
    }
    */
}
