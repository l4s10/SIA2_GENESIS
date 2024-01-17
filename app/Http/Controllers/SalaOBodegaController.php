<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Sala_O_Bodega;


class SalaOBodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener la OFICINA_ID del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;

        // Función que lista salas y bodegas basadas en la OFICINA_ID del usuario
        $salasobodegas = Sala_O_Bodega::where('OFICINA_ID', $oficinaIdUsuario)->get();

        return view('salasobodegas.index', compact('salasobodegas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('salasobodegas.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SALA_O_BODEGA_NOMBRE' => 'required|string|max:128',
                'SALA_O_BODEGA_CAPACIDAD' => 'nullable|integer|min:1|max:200',
                'SALA_O_BODEGA_ESTADO' => 'required|string|max:40',
                'SALA_O_BODEGA_TIPO' => ['required', 'string', 'max:20', Rule::in(['SALA', 'BODEGA'])],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Sala_O_Bodega::create([
                'SALA_O_BODEGA_NOMBRE' => strtoupper($request->input('SALA_O_BODEGA_NOMBRE')),
                'SALA_O_BODEGA_CAPACIDAD' => $request->input('SALA_O_BODEGA_CAPACIDAD'),
                'SALA_O_BODEGA_ESTADO' => strtoupper($request->input('SALA_O_BODEGA_ESTADO')),
                'SALA_O_BODEGA_TIPO' => $request->input('SALA_O_BODEGA_TIPO'),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            return redirect()->route('salasobodegas.index')->with('success', 'Sala o bodega creada con éxito');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la sala o bodega: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Puedes implementar lógica para mostrar el formulario de edición
        $salaobodega = Sala_O_Bodega::find($id);
        return view('salasobodegas.edit', compact('salaobodega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SALA_O_BODEGA_NOMBRE' => 'required|string|max:128',
                'SALA_O_BODEGA_CAPACIDAD' => 'nullable|integer|min:1|max:200',
                'SALA_O_BODEGA_ESTADO' => 'required|string|max:40',
                'SALA_O_BODEGA_TIPO' => ['required', 'string', 'max:20', Rule::in(['SALA', 'BODEGA'])],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $salaobodega = Sala_O_Bodega::find($id);
            $salaobodega->update([
                'SALA_O_BODEGA_NOMBRE' => strtoupper($request->input('SALA_O_BODEGA_NOMBRE')),
                'SALA_O_BODEGA_CAPACIDAD' => $request->input('SALA_O_BODEGA_CAPACIDAD'),
                'SALA_O_BODEGA_ESTADO' => strtoupper($request->input('SALA_O_BODEGA_ESTADO')),
                'SALA_O_BODEGA_TIPO' => $request->input('SALA_O_BODEGA_TIPO'),
            ]);

            return redirect()->route('salasobodegas.index')->with('success', 'Sala o bodega actualizada con éxito');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la sala o bodega: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Puedes implementar lógica para eliminar el recurso de la base de datos
            $salaobodega = Sala_O_Bodega::find($id);
            $salaobodega->delete();

            return redirect()->route('salaobodegas.index')->with('success', 'Sala o bodega eliminada con éxito');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la sala o bodega: ' . $e->getMessage());
        }
    }
}
