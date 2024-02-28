<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObedeceResolucion extends Model
{
    use HasFactory;

    protected $table = 'obedecen_resoluciones';
    public $incrementing = false;
    protected $fillable = [
        'RESOLUCION_ID',
        'CARGO_ID',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'CARGO_ID', 'CARGO_ID');
    }

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'RESOLUCION_ID', 'RESOLUCION_ID');
    }
}