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

    protected function authenticated(Request $request, $user)
    {
        $token = $user->createToken('API Token')->plainTextToken;

        // Almacenar el token en la sesión del usuario
        $request->session()->put('api_token', $token);

        // No necesitas retornar nada aquí si estás redirigiendo al usuario a otra página después del login
        // Laravel redirigirá al usuario a la página definida en redirectTo por defecto
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

        // Opcional: Regenerar el token de la sesión
        // $request->session()->regenerateToken();

        // Redirigir al usuario a la página de inicio de sesión o donde prefieras
        return redirect('/login');
    }


    public function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Success',
                'token' => $token,
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
}
