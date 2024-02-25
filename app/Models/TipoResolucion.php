<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoResolucion extends Model
{
    use HasFactory;

    protected $table = 'tipos_resoluciones';

    protected $primaryKey = 'TIPO_RESOLUCION_ID';

    protected $fillable = [
        'TIPO_RESOLUCION_NOMBRE',
    ];

    public function resoluciones()
    {
        return $this->hasMany(Resolucion::class, 'TIPO_RESOLUCION_ID');
    }
}

