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
        public function solicitante()
        {
            return $this->belongsTo(User::class, 'USUARIO_id', 'id');
        }
        // Relación de muchos a muchos con Material
        public function materiales()
        {
            return $this->belongsToMany(Material::class, 'solicitudes_materiales', 'SOLICITUD_ID', 'MATERIAL_ID')
                ->withPivot('SOLICITUD_MATERIAL_CANTIDAD')
                ->withPivot('SOLICITUD_MATERIAL_CANTIDAD_AUTORIZADA')
                ->withTimestamps();
        }

        // Relación de muchos a muchos con Formulario
        public function formularios()
        {
            return $this->belongsToMany(Formulario::class, 'solicitudes_formularios', 'SOLICITUD_ID', 'FORMULARIO_ID')
                ->withPivot('SOLICITUD_FORMULARIOS_CANTIDAD')
                ->withTimestamps();
        }

        // Relación de muchos a muchos con Tipos de equipos
        public function equipos()
        {
            return $this->belongsToMany(TipoEquipo::class, 'solicitudes_equipos', 'SOLICITUD_ID', 'TIPO_EQUIPO_ID')
                ->withPivot('SOLICITUD_EQUIPOS_CANTIDAD', 'SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA')
                ->withTimestamps();
        }

        // Relación de uno a uno con SolicitudSala
        public function salas()
        {
            return $this->hasOne(SolicitudSala::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
        }

        // Relación de uno a uno con SolicitudBodega
        public function bodegas()
        {
            return $this->hasOne(SolicitudBodega::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
        }
        // Relación de uno a muchos con RevisionSolicitud
        public function revisiones()
        {
            return $this->hasMany(RevisionSolicitud::class, 'SOLICITUD_ID', 'SOLICITUD_ID');
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
