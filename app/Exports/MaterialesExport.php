<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class MaterialesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    // Headings
    public function headings(): array
    {
        return [
            'NOMBRE_MATERIAL',
            'TIPO_MATERIAL',
            'STOCK',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $materiales = Material::where('OFICINA_ID', Auth::user()->OFICINA_ID)
            ->with('tipoMaterial') // Cargar la relación tipoMaterial
            ->get();
        // Transformar cada material en la colección
        $materiales = $materiales->map(function ($material) {
            return [
                'NOMBRE_MATERIAL' => $material->MATERIAL_NOMBRE,
                'TIPO_MATERIAL' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE, // Asegúrate de que 'NOMBRE' es el atributo correcto en tu modelo TipoMaterial
                'STOCK' => $material->MATERIAL_STOCK,
                // Agregar más campos aquí si es necesario
            ];
        });

        return $materiales;
    }
}
