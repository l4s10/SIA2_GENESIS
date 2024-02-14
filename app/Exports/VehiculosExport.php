<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\SolicitudVehicular;

class VehiculosExport implements FromCollection
{
    public function collection()
    {
        return SolicitudVehicular::all();
    }

}
