<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function attemptLogin(Request $request)
    {
        // Intenta autenticar al usuario
        $credentials = $this->credentials($request);
        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            // Si las credenciales proporcionadas son incorrectas, se lanza una excepción
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }

        // Verifica si el estado del usuario es 'INGRESADO'
        $user = Auth::user();
        if ($user->USUARIO_ESTADO !== 'INGRESADO') {
            // Si el estado del usuario no es 'INGRESADO', se desconecta y se lanza una excepción
            Auth::logout();
            throw ValidationException::withMessages([
                $this->username() => ['User account is not active.'],
            ]);
        }

        // Si el usuario se autentica correctamente y su estado es 'INGRESADO', se retorna verdadero
        return true;
    }

    public function logout(Request $request)
    {
        // Obtener al usuario autenticado
        $user = Auth::user();

        // Invalidar todos los tokens del usuario
        $user->tokens->each(function($token, $key) {
            $token->delete();
        });

        // Realizar el cierre de sesión estándar
        Auth::logout();

        // Opcional: Invalidar la sesión del usuario para prevenir que el ID de sesión sea utilizado
        $request->session()->invalidate();

        // Redirigir al usuario a la página de inicio de sesión o donde prefieras
        return redirect('/login');
    }

    public function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Intenta autenticar al usuario
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
    
            // Verifica si el estado del usuario es 'INGRESADO'
            if ($user->USUARIO_ESTADO === 'INGRESADO') {
                // Genera el token para el usuario autenticado
                $token = $user->createToken('API Token')->plainTextToken;
    
                return response()->json([
                    'message' => 'Success',
                    'token' => $token,
                ]);
            } else {
                // Si el estado del usuario no es 'INGRESADO', se desconecta y se lanza una excepción
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['User account is not active.'],
                ]);
            }
        }
    
        // Si las credenciales proporcionadas son incorrectas, se lanza una excepción
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
}
