<?php

namespace App\Http\Controllers\Movimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Importar el modelo Movimiento
use App\Models\Movimiento;

class MovimientosController extends Controller
{
    public function home()
    {
        return view('sia2.auditorias.home');
    }

    public function materiales()
    {
        // Consulta con Eloquent
        $auditorias = Movimiento::where('MOVIMIENTO_OBJETO', 'LIKE', 'MATERIAL: %')->get();

        return view('sia2.auditorias.materiales', compact('auditorias'));
    }

    public function equipos()
    {
        // Consulta con Eloquent
        $auditorias = Movimiento::where('MOVIMIENTO_OBJETO', 'LIKE', 'EQUIPO: %')->get();
        return view('sia2.auditorias.equipos', compact('auditorias'));
    }
}
