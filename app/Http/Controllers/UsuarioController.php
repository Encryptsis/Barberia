<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Importación añadida

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
        if (Auth::attempt(['usr_username' => $credentials['usr_username'], 'password' => $credentials['usr_password'], 'usr_activo' => true])) {
            // Autenticación exitosa
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Has iniciado sesión correctamente.');
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

        return redirect('/')->with('success', 'Has cerrado sesión exitosamente.');
    }

    /**
     * Mostrar la página de perfil del usuario.
     */
    public function perfil()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado

        // Verificar si el usuario está autenticado
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a tu perfil.');
        }

        return view('perfil.usuario', compact('usuario'));
    }

    /**
     * Actualizar la información del perfil del usuario.
     */
    public function actualizarPerfil(Request $request)
    {
        $usuario = Auth::user();
        
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'usr_nombre_completo' => 'required|string|max:100',
            'usr_correo_electronico' => 'required|email|max:100|unique:usuarios,usr_correo_electronico,' . $usuario->usr_id . ',usr_id',
            'usr_telefono' => 'nullable|string|max:20',
            'usr_foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Opcional: validar la foto
        ]);
    
        // Actualizar los campos
        $usuario->usr_nombre_completo = $validatedData['usr_nombre_completo'];
        $usuario->usr_correo_electronico = $validatedData['usr_correo_electronico'];
        $usuario->usr_telefono = $validatedData['usr_telefono'];
    
        // Manejar la subida de la foto de perfil si existe
        if ($request->hasFile('usr_foto_perfil')) {
            // Eliminar la foto anterior si existe
            if ($usuario->usr_foto_perfil) {
                // Extraer la ruta relativa ('fotos_perfil/filename.jpg')
                $fotoAnterior = str_replace('storage/', '', $usuario->usr_foto_perfil);
                if (Storage::disk('public')->exists($fotoAnterior)) {
                    Storage::disk('public')->delete($fotoAnterior);
                }
            }
    
            $image = $request->file('usr_foto_perfil');
            $name = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName()); // Reemplaza espacios por guiones bajos
            $path = $image->storeAs('fotos_perfil', $name, 'public'); // Almacenar en el disco 'public'
    
            // Actualizar la ruta de la foto en el usuario
            $usuario->usr_foto_perfil = $path; // 'fotos_perfil/filename.jpg'
        }
    
        $usuario->save();
    
        return redirect()->route('perfil.usuario')->with('success', 'Perfil actualizado exitosamente.');
    }

    public function agenda()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado

        // Obtener todos los servicios desde la base de datos
        $servicios = Servicio::all();

        // Pasar los servicios y el usuario a la vista 'usuario'
        return view('agenda.usuario', compact('usuario', 'servicios'));
    }

    public function getProfessionals($service_id)
    {
        // Validar que el servicio existe
        $servicio = Servicio::find($service_id);
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado.'], 404);
        }
    
        // Obtener los usuarios (profesionales) que pueden realizar el servicio
        $profesionales = $servicio->usuarios()
            ->where('usuarios.usr_activo', 1) // Especificar la tabla para evitar ambigüedad
            ->get(['usuarios.usr_id', 'usuarios.usr_nombre_completo']);
    
        // Devolver los profesionales en formato JSON
        return response()->json($profesionales);
    }
    

    
}
    
