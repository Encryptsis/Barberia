<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Importación añadida
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\PaymentMethod;

class UsuarioController extends Controller
{
    /**
     * Mostrar el formulario de registro.
     */
    public function create()
    {
        Stripe::setApiKey(config('stripe.secret'));
    
        // Crear un SetupIntent para capturar el método de pago
        $setupIntent = SetupIntent::create([
            'payment_method_types' => ['card'],
        ]);
    
        return view('auth.register', [
            'stripeKey' => config('stripe.key'),
            'clientSecret' => $setupIntent->client_secret,
        ]);
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
            'payment_method_id' => 'required|string', // Nuevo campo para Payment Method de Stripe
        ]);

        // Obtener el rol 'Cliente' dinámicamente
        $clienteRole = Role::where('rol_nombre', 'Cliente')->first();

        if (!$clienteRole) {
            // Manejar el error si el rol 'Cliente' no existe
            return redirect()->back()->with('error', 'El rol "Cliente" no está definido en el sistema.');
        }

        // Crear el Customer en Stripe
        Stripe::setApiKey(config('stripe.secret')); // Usa el archivo de configuración
        try {
            $customer = Customer::create([
                'email' => $validatedData['usr_correo_electronico'],
                'name' => $validatedData['usr_nombre_completo'],
                'payment_method' => $validatedData['payment_method_id'],
                'invoice_settings' => [
                    'default_payment_method' => $validatedData['payment_method_id'],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear el Customer en Stripe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al procesar tu método de pago. Inténtalo de nuevo.');
        }

        // Crear el nuevo usuario con rol 'Cliente' y datos de Stripe
        $usuario = Usuario::create([
            'usr_username' => $validatedData['usr_username'],
            'usr_password' => Hash::make($validatedData['usr_password']),
            'usr_nombre_completo' => $validatedData['usr_nombre_completo'],
            'usr_telefono' => $validatedData['usr_telefono'],
            'usr_correo_electronico' => $validatedData['usr_correo_electronico'],
            'usr_activo' => true,
            'usr_rol_id' => $clienteRole->rol_id, // Asigna el rol 'Cliente' dinámicamente
            'stripe_customer_id' => $customer->id, // Guarda el Customer ID de Stripe
            'stripe_payment_method_id' => $validatedData['payment_method_id'], // Guarda el Payment Method ID de Stripe
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
    public function perfil($username)
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado

        // Verificar si el usuario está autenticado
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a tu perfil.');
        }

        // Verificar si el nombre de usuario en la URL coincide con el usuario autenticado
        if ($usuario->usr_username !== $username) {
            return redirect()->route('perfil.usuario', ['username' => $usuario->usr_username])->with('error', 'No tienes permiso para acceder a este perfil.');
        }

        return view('profile.profile_user', compact('usuario'));
    }

    /**
     * Actualizar la información del perfil del usuario.
     */
    public function actualizarPerfil(Request $request, $username)
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado

        // Verificar si el usuario está autenticado
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para actualizar tu perfil.');
        }

        // Verificar si el nombre de usuario en la URL coincide con el usuario autenticado
        if ($usuario->usr_username !== $username) {
            return redirect()->route('perfil.usuario', ['username' => $usuario->usr_username])->with('error', 'No tienes permiso para actualizar este perfil.');
        }

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
            if ($usuario->usr_foto_perfil && Storage::disk('public')->exists($usuario->usr_foto_perfil)) {
                Storage::disk('public')->delete($usuario->usr_foto_perfil);
            }

            $image = $request->file('usr_foto_perfil');
            $name = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName()); // Reemplaza espacios por guiones bajos
            $path = $image->storeAs('fotos_perfil', $name, 'public'); // Almacenar en el disco 'public'

            // Actualizar la ruta de la foto en el usuario
            $usuario->usr_foto_perfil = $path; // 'fotos_perfil/filename.jpg'
        }

        $usuario->save();

        return redirect()->route('perfil.usuario', ['username' => $usuario->usr_username])->with('success', 'Perfil actualizado exitosamente.');
    }


    public function agenda()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
    
        // Calcular los puntos de fidelidad del usuario
        $userPoints = $usuario->usr_points ?? 0; // Usa 0 como predeterminado si no existe
    
        // Obtener todos los servicios desde la base de datos
        $servicios = Servicio::all();
    
        // Pasar los servicios, el usuario y los puntos a la vista 'usuario'
        return view('agendas.client_schedule', compact('usuario', 'servicios', 'userPoints'));
    }
    
    public function getProfessionals($service_id)
    {

        Log::info("getProfessionals llamado con service_id: {$service_id}");
        // Validar que el servicio existe
        $servicio = Servicio::find($service_id);
        if (!$servicio) {
            Log::warning("Servicio con ID {$service_id} no encontrado.");
            return response()->json(['error' => 'Servicio no encontrado.'], 404);
        }
    
        // Obtener los usuarios (profesionales) que pueden realizar el servicio
        $profesionales = $servicio->usuarios()
            ->where('usuarios.usr_activo', 1) // Especificar la tabla para evitar ambigüedad
            ->get(['usuarios.usr_id', 'usuarios.usr_nombre_completo']);
        Log::info("Profesionales encontrados para service_id {$service_id}: " . $profesionales->count());
        // Devolver los profesionales en formato JSON
        return response()->json($profesionales);
    }
    

    
}
    
