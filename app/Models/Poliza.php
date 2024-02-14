<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poliza extends Model
{
    protected $table = 'polizas';

    protected $primaryKey = 'POLIZA_ID';

    public $timestamps = true;

    protected $fillable = [
        'USUARIO_id',
        'OFICINA_ID',
        'POLIZA_FECHA_VENCIMIENTO_LICENCIA',
        'POLIZA_NUMERO',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'USUARIO_id', 'id');
    }

    // Relacion uno a uno con oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'OFICINA_ID');
    }
}