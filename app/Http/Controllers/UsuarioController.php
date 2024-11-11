<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Mostrar el formulario de registro.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Procesar y almacenar el nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'usr_username' => 'required|string|max:50|unique:usuarios,usr_username',
            'usr_password' => 'required|string|min:8|confirmed', // 'confirmed' espera 'usr_password_confirmation'
            'usr_nombre_completo' => 'required|string|max:100',
            'usr_telefono' => 'nullable|string|max:20',
            'usr_correo_electronico' => 'required|email|max:100|unique:usuarios,usr_correo_electronico',
        ]);

        // Crear el nuevo usuario con rol por defecto (Cliente)
        $usuario = Usuario::create([
            'usr_username' => $validatedData['usr_username'],
            'usr_password' => Hash::make($validatedData['usr_password']),
            'usr_nombre_completo' => $validatedData['usr_nombre_completo'],
            'usr_telefono' => $validatedData['usr_telefono'],
            'usr_correo_electronico' => $validatedData['usr_correo_electronico'],
            'usr_activo' => true,
            'usr_rol_id' => 4, // Asigna el rol 'Cliente' por defecto
        ]);

        // Autenticar al usuario recién registrado
        Auth::login($usuario);
        // Depurar el usuario autenticado
        //dd(Auth::user());

        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Redireccionar a la página de inicio con un mensaje de éxito
            return redirect()->route('home')->with('success', 'Usuario registrado y autenticado exitosamente.');
        } else {
            // Si no está autenticado, redireccionar al inicio de sesión
            return redirect()->route('login')->with('error', 'No se pudo iniciar sesión automáticamente. Por favor, inicia sesión.');
        }

    }

    /**
     * Mostrar el formulario de inicio de sesión.
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el inicio de sesión.
     */
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $credentials = $request->validate([
            'usr_username' => 'required|string',
            'usr_password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt([
            'usr_username' => $credentials['usr_username'],
            'usr_password' => $credentials['usr_password'],
            'usr_activo' => true
        ])) {
            // Autenticación exitosa
            $request->session()->regenerate();

            return redirect()->intended('home')->with('success', 'Has iniciado sesión correctamente.');
        }

        // Autenticación fallida
        return back()->withErrors([
            'usr_username' => 'Las credenciales proporcionadas no coinciden con nuestros registros o el usuario está inactivo.',
        ])->onlyInput('usr_username');
    }


    /**
     * Procesar el cierre de sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
