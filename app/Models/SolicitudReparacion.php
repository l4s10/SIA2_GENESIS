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
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaReparacion::class, 'CATEGORIA_REPARACION_ID', 'CATEGORIA_REPARACION_ID');
    }

    // Relacion con revision_solicitudes a traves de su ID
    public function revisiones()
    {
        return $this->hasMany(RevisionSolicitud::class, 'SOLICITUD_REPARACION_ID', 'SOLICITUD_REPARACION_ID');
    }

    // Relacion con vehiculos a traves de su ID
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'VEHICULO_ID', 'VEHICULO_ID');
    }
    
    // Funcion para devolver las fechas en formato correcto CHILE
    public function mostrarFecha($value)
    {
        //caso vacio retornar nulo
        if ($value == null) {
            return null;
        }
        return date('d-m-Y H:i', strtotime($value));
    }
}
