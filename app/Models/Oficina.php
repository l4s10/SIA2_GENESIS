<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    use HasFactory;

    protected $table = 'oficinas';

    protected $primaryKey = 'OFICINA_ID';

    protected $fillable = [
        'COMUNA_ID',
        'OFICINA_NOMBRE',
    ];

    //* Relación de uno y solo uno con Comuna*/
    /*
        Relacion de uno a uno -> BelongsTo
        Relacion de uno a muchos / cero a muchos -> HasMany
    */
    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'COMUNA_ID', 'COMUNA_ID');
    }

    //* Relación de uno y a muchos con Departamento*/
    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'OFICINA_ID', 'OFICINA_ID');
    }
    //*Relación de uno a muchos con Ubicacion */
    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'OFICINA_ID', 'OFICINA_ID');
    }
    //*Relación de uno a muchos con Grupo */
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'OFICINA_ID', 'OFICINA_ID');
    }
    //*Relación de uno a muchos con Escalafon */
    public function escalafones()
    {
        return $this->hasMany(Escalafon::class, 'OFICINA_ID', 'OFICINA_ID');
    }
    //*Relación de uno a muchos con Grado */
    public function grados()
    {
        return $this->hasMany(Grado::class, 'OFICINA_ID', 'OFICINA_ID');
    }
    //*Relación de uno a muchos con Cargo */
    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'OFICINA_ID', 'OFICINA_ID');
    }
    //*Relacion de uno a muchos con user */
    public function users()
    {
        return $this->hasMany(User::class, 'OFICINA_ID', 'OFICINA_ID');
    }
}
