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
}
