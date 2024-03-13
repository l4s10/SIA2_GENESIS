<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

// Importar modelos
use App\Models\User; //Importamos user pero en este contexto lo llamamos Usuario.
use App\Models\Region;
use App\Models\Ubicacion;
use App\Models\Departamento;
use App\Models\Grupo;
use App\Models\Escalafon;
use App\Models\Grado;
use App\Models\CalidadJuridica;
use App\Models\Cargo;
use App\Models\Oficina;
use App\Utils\RutUtils;


class UsuarioController extends Controller
{
    /*public function __construct()
    {
        // * SI LA PERSONA ES ADMINISTRADOR O INFORMATICO TIENE ACCESO A TODOS LAS RUTAS* (le quitamos get usuarios pero se la volvemos a asignar en la siguiente)
        $this->middleware(['auth', 'roleAdminAndServices'])->except('getUsuarios');
        // * SI LA PERSONA TIENE CUALQUIER ROL, SOLO PODRAN ACCEDER AL METODO "getUsuarios" (ESTA FUNCION ES EL DESPLEGABLE DE TODAS LAS PAGINAS QUE PERMITEN SELECCIONAR USUARIOS PARA SOLICITUDES)*
        $this->middleware('checkAnyRole')->only('getUsuarios');
    }*/
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //try-catch para manejar errores
        try {
            // Listar todos los usuarios que esten dentro de la misma direccion regional que el usuario logueado.
            $usuarios = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Retornar vista con usuarios
            return view('sia2.panel.usuarios.index', compact('usuarios'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al listar usuarios');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //try-catch para manejar errores
        try {
            $roles = Role::all();
            $regiones = Region::all();
            $ubicaciones = Ubicacion::all();
            $departamentos = Departamento::all();
            $grupos = Grupo::all();
            $escalafones = Escalafon::all();
            $grados = Grado::all();
            $oficinas = Oficina::all();
            //Cargos filtrar por ubicacion de usuario
            //$ubicacionUser = Ubicacion::findOrFail(Auth::user()->ID_UBICACION);
            //$direccionFiltradaId = $ubicacionUser->direccion->ID_DIRECCION;
            $cargos = Cargo::all();

            // Retornar vista con formulario para crear usuario
            return view('sia2.panel.usuarios.create',compact('roles','ubicaciones','departamentos','regiones','grupos','escalafones','grados','cargos','oficinas'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para crear usuario');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        try{
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'oficina' => 'required|exists:oficinas,OFICINA_ID',
                'DEPARTAMENTO_ID' => 'nullable|exists:departamentos,DEPARTAMENTO_ID',
                'UBICACION_ID' => 'nullable|exists:ubicaciones,UBICACION_ID',
                'GRUPO_ID' => 'required|exists:grupos,GRUPO_ID',
                'ESCALAFON_ID' => 'required|exists:escalafones,ESCALAFON_ID',
                'GRADO_ID' => 'required|exists:grados,GRADO_ID',
                'CARGO_ID' => 'required|exists:cargos,CARGO_ID',
                'USUARIO_NOMBRES' => 'required|string|max:255',
                'USUARIO_APELLIDOS' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|unique:users,password|confirmed',
                'USUARIO_RUT' => 'required|string|max:20|unique:users,USUARIO_RUT',
                'USUARIO_FECHA_NAC' => 'required|date',
                'USUARIO_FECHA_INGRESO' => 'required|date',
                'USUARIO_FONO' => 'required|string|max:12',
                'USUARIO_ANEXO' => 'required|string|max:10',
                'USUARIO_CALIDAD_JURIDICA' => 'required|string|max:12',
                'USUARIO_SEXO' => 'required|string|max:30',
            ], [
                'OFICINA_ID.required' => 'El campo "Dirección regional asociada" es requerido',
                'OFICINA_ID.exists' => 'El campo "Dirección regional asociada" no es válido.',
                'DEPARTAMENTO_ID.exists' => 'El campo "Departamento" no es válido.',
                'UBICACION_ID.exists' => 'El campo "Ubicación" no es válido.',
                'GRUPO_ID.required' => 'El campo "Grupo" es requerido',
                'GRUPO_ID.exists' => 'El campo "Grupo" no es válido.',
                'ESCALAFON_ID.required' => 'El campo "Escalafón" es requerido',
                'ESCALAFON_ID.exists' => 'El campo "Escalafón" no es válido.',
                'GRADO_ID.required' => 'El campo "Grado" es requerido',
                'GRADO_ID.exists' => 'El campo "Grado" no es válido.',
                'CARGO_ID.required' => 'El campo "Cargo" es requerido',
                'CARGO_ID.exists' => 'El campo "Cargo" no es válido.',
                'USUARIO_NOMBRES.required' => 'El campo "Nombres" es requerido',
                'USUARIO_NOMBRES.max' => 'El campo "Nombres" no debe exceder los :max caracteres',
                'USUARIO_APELLIDOS.required' => 'El campo "Apellidos" es requerido',
                'USUARIO_APELLIDOS.max' => 'El campo "Apellidos" no debe exceder los :max caracteres',
                'email.required' => 'El campo "Email" es requerido',
                'email.email' => 'El campo "Email" debe ser una dirección de correo electrónico válida',
                'email.unique' => 'El campo "Email" ya está en uso',
                'password.required' => 'El campo "Contraseña" es requerido',
                'password.min' => 'La contraseña debe tener al menos :min caracteres',
                'password.unique' => 'La contraseña ya está en uso',
                'password.confirmed' => 'La confirmación de la contraseña no coincide',
                'USUARIO_RUT.required' => 'El campo "RUT" es requerido',
                'USUARIO_RUT.max' => 'El campo "RUT" no debe exceder los :max caracteres',
                'USUARIO_RUT.unique' => 'El campo "RUT" ya está en uso',
                'USUARIO_FECHA_NAC.required' => 'El campo "Fecha de Nacimiento" es requerido',
                'USUARIO_FECHA_INGRESO.required' => 'El campo "Fecha de Ingreso" es requerido',
                'USUARIO_FONO.required' => 'El campo "Teléfono" es requerido',
                'USUARIO_FONO.max' => 'El campo "Teléfono" no debe exceder los :max caracteres',
                'USUARIO_ANEXO.required' => 'El campo "Anexo" es requerido',
                'USUARIO_ANEXO.max' => 'El campo "Anexo" no debe exceder los :max caracteres',
                'USUARIO_CALIDAD_JURIDICA.required' => 'El campo "Calidad Jurídica" es requerido',
                'USUARIO_CALIDAD_JURIDICA.max' => 'El campo "Calidad Jurídica" no debe exceder los :max caracteres',
                'USUARIO_SEXO.required' => 'El campo "Sexo" es requerido',
                'USUARIO_SEXO.max' => 'El campo "Sexo" no debe exceder los :max caracteres',
            ]);

            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                //dd($validator);
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                // Crear el usuario
                User::create([
                    'OFICINA_ID' => $request->oficina,
                    'DEPARTAMENTO_ID' => $request->DEPARTAMENTO_ID,
                    'UBICACION_ID' => $request->UBICACION_ID,
                    'GRUPO_ID' => $request->GRUPO_ID,
                    'ESCALAFON_ID' => $request->ESCALAFON_ID,
                    'GRADO_ID' => $request->GRADO_ID,
                    'CARGO_ID' => $request->CARGO_ID,
                    'USUARIO_NOMBRES' => $request->USUARIO_NOMBRES,
                    'USUARIO_APELLIDOS' => $request->USUARIO_APELLIDOS,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'USUARIO_RUT' => $request->USUARIO_RUT,
                    'USUARIO_FECHA_NAC' => $request->USUARIO_FECHA_NAC,
                    'USUARIO_FECHA_INGRESO' => $request->USUARIO_FECHA_INGRESO,
                    'USUARIO_FONO' => $request->USUARIO_FONO,
                    'USUARIO_ANEXO' => $request->USUARIO_ANEXO,
                    'USUARIO_CALIDAD_JURIDICA' => $request->USUARIO_CALIDAD_JURIDICA,
                    'USUARIO_SEXO' => $request->USUARIO_SEXO,
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.usuarios.index')->with('success', 'Usuario agregado exitosamente');
            }

        }catch(\Exception $e){
            dd($e);
            session()->flash('error','Hubo un error al agregar el usuario. Por favor, inténtelo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //try-catch para manejar errores
        try {
            // Buscar usuario por id que este en la misma direccion regional
            $usuario = Usuario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);

            // Retornar vista con usuario
            return view('sia2.panel.usuarios.show', compact('usuario'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar usuario');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para manejar errores
        try {
            // Buscar usuario por id que este en la misma direccion regional
            $usuario = Usuario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);

            // Retornar vista con formulario para editar usuario
            return view('sia2.panel.usuarios.edit', compact('usuario'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para editar usuario');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
