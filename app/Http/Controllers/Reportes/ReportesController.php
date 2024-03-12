<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * CONTROLADOR PARA EL MANEJO DE LAS VISTAS DE LOS REPORTES.
    */
    public function Home(){
        return view('sia2.reportes.home');
    }

    public function Materiales(){
        return view('sia2.reportes.materiales');
    }

    public function Equipos(){
        return view('sia2.reportes.equipos');
    }

    public function Salas(){
        return view('sia2.reportes.salas');
    }

    public function Bodegas(){
        return view('sia2.reportes.bodegas');
    }

    public function Reparaciones(){
        return view('sia2.reportes.reparaciones');
    }

    public function Sistema(){
        //Obtener todas las regiones
        $regiones = \App\Models\Region::all();
        //Obtener todas las oficinas
        $oficinas = \App\Models\Oficina::all();
        //Obtener todos los departamentos
        $departamentos = \App\Models\Departamento::all();
        //Obtener todos las ubicaciones
        $ubicaciones = \App\Models\Ubicacion::all();

        return view('sia2.reportes.sistema', compact('regiones', 'oficinas', 'departamentos', 'ubicaciones'));
    }

    public function Vehiculos(){
        return view('sia2.reportes.vehiculos');
    }
}
