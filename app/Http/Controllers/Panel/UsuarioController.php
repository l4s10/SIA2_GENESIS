<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;


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
            // Listar todos los usuarios que estén dentro de la misma dirección regional que el usuario logueado
            // y que tengan estado "INGRESADO"
            $usuarios = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)
                            ->where('USUARIO_ESTADO', 'INGRESADO')
                            ->get();
    
            // Retornar vista con usuarios
            return view('sia2.panel.usuarios.index', compact('usuarios'));
        } catch (Exception $e) {
            // Retornar vista con mensaje de error a través de session
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
                if (!$this->validarRut($request->USUARIO_RUT)) {
                    $validator->errors()->add('USUARIO_RUT', 'El RUT ingresado no es válido. Por favor, verifique dígitos y formato.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                // Crear el usuario
                $user = User::create([
                    'OFICINA_ID' => $request->oficina,
                    'GRUPO_ID' => $request->GRUPO_ID,
                    'ESCALAFON_ID' => $request->ESCALAFON_ID,
                    'GRADO_ID' => $request->GRADO_ID,
                    'CARGO_ID' => $request->CARGO_ID,
                    'USUARIO_NOMBRES' =>strtoupper($request->input('USUARIO_NOMBRES')),
                    'USUARIO_APELLIDOS' => strtoupper($request->input('USUARIO_APELLIDOS')),
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'USUARIO_RUT' => $request->USUARIO_RUT,
                    'USUARIO_FECHA_NAC' => $request->USUARIO_FECHA_NAC,
                    'USUARIO_FECHA_INGRESO' => $request->USUARIO_FECHA_INGRESO,
                    'USUARIO_FONO' => $request->USUARIO_FONO,
                    'USUARIO_ANEXO' => $request->USUARIO_ANEXO,
                    'USUARIO_CALIDAD_JURIDICA' => $request->USUARIO_CALIDAD_JURIDICA,
                    'USUARIO_SEXO' => $request->USUARIO_SEXO,
                    'USUARIO_ESTADO' => 'INGRESADO'
                ]);

                if($request->input('tipo_dependencia')==='Departamentos'){
                    $user->DEPARTAMENTO_ID = $request->input('dependencia');
                } else {
                    $user->UBICACION_ID = $request->input('dependencia');
                }

                $user->save();

                // Asignar el rol al usuario
                $user->roles()->attach($request->role);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.usuarios.index')->with('success', 'Usuario agregado exitosamente');
            }

        }catch(\Exception $e){
            //dd($e);
            return redirect()->route('panel.usuarios.index')->with('error','Hubo un error al agregar el usuario. Por favor, inténtelo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     */
    /*public function show(string $id)
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
    }*/

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para manejar errores
        try {
            // Buscar usuario por id que esté en la misma dirección regional
            $usuario = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);
    
            // Obtener las mismas colecciones de datos que en el método create
            $roles = Role::all();
            $regiones = Region::all();
            $ubicaciones = Ubicacion::all();
            $departamentos = Departamento::all();
            $grupos = Grupo::all();
            $escalafones = Escalafon::all();
            $grados = Grado::all();
            $oficinas = Oficina::all();
            $cargos = Cargo::all();
    
            // Retornar vista con formulario para editar usuario y las colecciones de datos
            return view('sia2.panel.usuarios.edit', compact('usuario', 'roles', 'ubicaciones', 'departamentos', 'regiones', 'grupos', 'escalafones', 'grados', 'cargos', 'oficinas'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a través de session
            return back()->with('error', 'Error al mostrar formulario para editar usuario');
        }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);
        // try-catch para manejar errores
        try {
            // Buscar el usuario por su ID
            $usuario = User::findOrFail($id);
    
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'oficina' => 'required|exists:oficinas,OFICINA_ID',
                'ESCALAFON_ID' => 'required|exists:escalafones,ESCALAFON_ID',
                'GRADO_ID' => 'required|exists:grados,GRADO_ID',
                'CARGO_ID' => 'required|exists:cargos,CARGO_ID',
                'USUARIO_NOMBRES' => 'required|string|max:255',
                'USUARIO_APELLIDOS' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$usuario->id.',id',
                'password' => 'nullable|string|min:6|confirmed',
                'USUARIO_RUT' => 'required|string|max:20|unique:users,USUARIO_RUT,'.$usuario->id.',id',
                'USUARIO_FECHA_NAC' => 'required|date',
                'USUARIO_FECHA_INGRESO' => 'required|date',
                'USUARIO_FONO' => 'required|string|max:12',
                'USUARIO_ANEXO' => 'required|string|max:10',
                'USUARIO_CALIDAD_JURIDICA' => 'required|string|max:12',
                'USUARIO_SEXO' => 'required|string|max:30',
            ], [
                'oficina.required' => 'El campo "Dirección regional asociada" es requerido',
                'oficina.exists' => 'El campo "Dirección regional asociada" no es válido',
                'ESCALAFON_ID.required' => 'El campo "Escalafón" es requerido',
                'ESCALAFON_ID.exists' => 'El campo "Escalafón" no es válido',
                'GRADO_ID.required' => 'El campo "Grado" es requerido',
                'GRADO_ID.exists' => 'El campo "Grado" no es válido',
                'CARGO_ID.required' => 'El campo "Cargo" es requerido',
                'CARGO_ID.exists' => 'El campo "Cargo" no es válido',
                'USUARIO_NOMBRES.required' => 'El campo "Nombres" es requerido',
                'USUARIO_NOMBRES.max' => 'El campo "Nombres" no debe exceder los :max caracteres',
                'USUARIO_APELLIDOS.required' => 'El campo "Apellidos" es requerido',
                'USUARIO_APELLIDOS.max' => 'El campo "Apellidos" no debe exceder los :max caracteres',
                'email.required' => 'El campo "Email" es requerido',
                'email.email' => 'El campo "Email" debe ser una dirección de correo electrónico válida',
                'email.unique' => 'El campo "Email" ya está en uso',
                'password.min' => 'La contraseña debe tener al menos :min caracteres',
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
    
            // Validar y redireccionar con mensajes de error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                if (!$this->validarRut($request->USUARIO_RUT)) {
                    $validator->errors()->add('USUARIO_RUT', 'El RUT ingresado no es válido. Por favor, verifique dígitos y formato.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                // Actualizar los atributos del usuario con los datos del formulario
                $usuario->update([
                    'OFICINA_ID' => $request->oficina,
                    'GRUPO_ID' => $request->GRUPO_ID,
                    'ESCALAFON_ID' => $request->ESCALAFON_ID,
                    'GRADO_ID' => $request->GRADO_ID,
                    'CARGO_ID' => $request->CARGO_ID,
                    'USUARIO_NOMBRES' =>strtoupper($request->input('USUARIO_NOMBRES')),
                    'USUARIO_APELLIDOS' => strtoupper($request->input('USUARIO_APELLIDOS')),
                    'email' => $request->email,
                    'USUARIO_RUT' => $request->USUARIO_RUT,
                    'USUARIO_FECHA_NAC' => $request->USUARIO_FECHA_NAC,
                    'USUARIO_FECHA_INGRESO' => $request->USUARIO_FECHA_INGRESO,
                    'USUARIO_FONO' => $request->USUARIO_FONO,
                    'USUARIO_ANEXO' => $request->USUARIO_ANEXO,
                    'USUARIO_CALIDAD_JURIDICA' => $request->USUARIO_CALIDAD_JURIDICA,
                    'USUARIO_SEXO' => $request->USUARIO_SEXO,
                ]);

                // Si se proporcionó una nueva contraseña, actualizarla
                if ($request->input('password') !== NULL) {
                    $usuario->update(['password' => bcrypt($request->password)]);
                }

                if ($request->has('role')) {
                    $usuario->roles()->sync([$request->role]);
                }

                if ($request->input('tipo_dependencia') !== null) {
                    $usuario->update([
                        'DEPARTAMENTO_ID' => $request->tipo_dependencia == 'Departamentos' ? $request->dependencia : null,
                        'UBICACION_ID' => $request->tipo_dependencia == 'Ubicaciones' ? $request->dependencia : null,
                    ]);
                }
    
                // Retornar la vista con un mensaje de éxito
                return redirect()->route('panel.usuarios.index')->with('success', 'Usuario actualizado exitosamente');
            }
        } catch (\Exception $e) {
            // Retornar vista con mensaje de error a través de session
            return back()->with('error', 'Error al actualizar el usuario');
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Buscar el usuario por su ID
            $usuario = User::findOrFail($id);
            $usuario->USUARIO_ESTADO = 'ELIMINADO';
            // Actualizar usuario
            $usuario->save();
            //dd($usuario);
            // Retornar la vista con un mensaje de éxito
            return redirect()->route('panel.usuarios.index')->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            // Retornar vista con mensaje de error a través de session
            return back()->with('error', 'Error al eliminar el usuario');
        }
    }

    function validarRut($rut) {
        // Verificar el formato del RUT (sin puntos pero con guión)
        if (!preg_match('/^[0-9]{7,8}-[0-9Kk]$/', $rut)) {
            echo "Formato de RUT incorrecto. Por favor ingréselo sin puntos y con guión.";
            return false;
        }
    
        // Despejar Guión
        $valor = str_replace('-', '', $rut);
        
        // Aislar Cuerpo y Dígito Verificador
        $cuerpo = substr($valor, 0, -1);
        $dv = strtoupper(substr($valor, -1));
        
        // Calcular Dígito Verificador
        $suma = 0;
        $multiplo = 2;
        
        for ($i = 1; $i <= strlen($cuerpo); $i++) {
            // Obtener el Producto con el Múltiplo Correspondiente
            $index = $multiplo * $cuerpo[strlen($cuerpo) - $i];
            
            // Sumar al Contador General
            $suma += $index;
            
            // Consolidar el Múltiplo dentro del rango [2, 7]
            if ($multiplo < 7) {
                $multiplo++;
            } else {
                $multiplo = 2;
            }
        }
        
        // Calcular Dígito Verificador en base al Módulo 11
        $dvEsperado = 11 - ($suma % 11);
        
        // Casos Especiales (0 y K)
        $dv = ($dv == 'K') ? 10 : $dv;
        $dv = ($dv == 0) ? 11 : $dv;
        
        // Validar que el Cuerpo coincide con su Dígito Verificador
        if ($dvEsperado != $dv) {
            echo "Formato de RUT incorrecto. Por favor ingréselo sin puntos y con guión.";
            return false;
        }
        
        // Si todo sale bien, es válido
        return true;
    }
}
