<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function Home(){
        return view('sia2.reportes.home');
    }

    public function Materiales(){
        return view('sia2.reportes.materiales');
    }
}
