<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use App\Models\PtsTransaction;
use Illuminate\Support\Facades\Log;

//Manejará las operaciones CRUD básicas de las citas, así como la creación de nuevas citas y la visualización de la agenda.
class CitaController extends Controller
{
    public function edit(Cita $cita)
    {
        $user = Auth::user();
    
        // Verificar si el usuario es el cliente o el profesional asignado
        if ($cita->cta_cliente_id !== $user->usr_id && $cita->cta_profesional_id !== $user->usr_id) {
            return redirect()->route('mi.agenda')->with('error', 'No tienes permiso para editar esta cita.');
        }
    
        // Obtener todos los servicios para el dropdown
        $servicios = Servicio::all();
    
        // Obtener los profesionales asociados al servicio actual de la cita
        $servicioActual = $cita->servicios->first();
        $profesionales = $servicioActual ? $servicioActual->usuarios()->get() : collect();
    
        return view('appointments.user_appointments_edit', compact('cita', 'servicios', 'profesionales'));
    }

    public function update(Request $request, Cita $cita)
    {
        $user = Auth::user();
    
        // Verificar si el usuario es el cliente o el profesional asignado
        if ($cita->cta_cliente_id !== $user->usr_id && $cita->cta_profesional_id !== $user->usr_id) {
            return redirect()->route('mi.agenda')->with('error', 'No tienes permiso para actualizar esta cita.');
        }
    
        // Validar los datos del formulario
        $request->validate([
            'service'   => 'required|integer|exists:servicios,srv_id',
            'attendant' => 'required|integer|exists:usuarios,usr_id',
            'fecha'     => 'required|date|after_or_equal:today',
            'hora'      => 'required|date_format:H:i',
        ], [
            'hora.date_format' => 'El campo hora debe tener el formato HH:MM.',
        ]);
    
        // Procesar la hora para asegurarse de que los minutos son '00'
        $horaConSegundos = $request->input('hora') . ':00';
    
        // Verificar si el intervalo está disponible (excepto para la cita actual)
        $existingAppointment = Cita::where('cta_profesional_id', $request->input('attendant'))
            ->where('cta_fecha', $request->input('fecha'))
            ->where('cta_hora', $horaConSegundos)
            ->where('cta_id', '!=', $cita->cta_id)
            ->first();
    
        if ($existingAppointment) {
            return redirect()->back()->withErrors(['hora' => 'El intervalo seleccionado ya está reservado. Por favor, elige otro.']);
        }
    
        // Actualizar la cita con los nuevos datos
        $cita->cta_profesional_id = $request->input('attendant');
        $cita->cta_fecha = $request->input('fecha');
        $cita->cta_hora = $horaConSegundos;
        // Actualizar otros campos si es necesario
    
        $cita->save();
    
        // Actualizar los servicios asociados
        $cita->servicios()->sync([$request->input('service')]);
    
        return redirect()->route('my-appointments')->with('success', 'Cita actualizada exitosamente.');
    }

    /*public function destroy(Cita $cita)
    {
        Log::info('Método destroy llamado', [
            'cta_id' => $cita->cta_id,
            'cta_activa' => $cita->cta_activa,
            'usuario_id' => Auth::id(),
        ]);
    
        $user = Auth::user();
    
        // Verificar si el usuario es el cliente o el profesional asignado
        if ($cita->cta_cliente_id !== $user->usr_id && $cita->cta_profesional_id !== $user->usr_id) {
            Log::warning('Intento de cancelación no autorizado', [
                'cta_id' => $cita->cta_id,
                'usuario_id' => $user->usr_id,
            ]);
            return redirect()->route('my-appointments')->with('error', 'No tienes permiso para eliminar esta cita.');
        }
    
        try {
            $cita->cta_activa = false;
            $cita->save();
    
            Log::info('Cita cancelada exitosamente', [
                'cta_id' => $cita->cta_id,
                'cta_activa' => $cita->cta_activa,
                'usuario_id' => $user->usr_id,
            ]);
    
            return redirect()->route('my-appointments')->with('success_delete', 'Cita cancelada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al cancelar la cita', [
                'cta_id' => $cita->cta_id,
                'usuario_id' => $user->usr_id,
                'mensaje' => $e->getMessage(),
            ]);
            return redirect()->route('my-appointments')->with('error', 'Ocurrió un error al cancelar esta cita. Por favor, inténtalo de nuevo.');
        }
    }
    */

    public function saveAppointment(Request $request)
    {
        try {
            Log::info('Entrando en saveAppointment');
    
            // Verificar autenticación
            $user = Auth::user();
            Log::info('Usuario autenticado:', ['user_id' => $user ? $user->usr_id : 'null']);
    
            if (!$user) {
                Log::warning('Usuario no autenticado en saveAppointment');
                return response()->json(['error' => 'No estás autenticado. Por favor, inicia sesión.'], 401);
            }
    
            // Definir reglas de validación
            $rules = [
                'service_id' => 'required|integer|exists:servicios,srv_id',
                'professional_id' => 'required|integer|exists:usuarios,usr_id',
                'fecha' => 'required|date_format:Y-m-d',
                'hora' => 'required|date_format:H:i:s',
                'use_free_appointment' => 'sometimes|boolean',
            ];
    
            $request->validate($rules, [
                'hora.date_format' => 'El campo hora debe tener el formato HH:MM:SS.',
            ]);
    
            Log::info('Datos validados correctamente');
    
            $serviceId = $request->input('service_id');
            $professionalId = $request->input('professional_id');
            $fecha = $request->input('fecha');
            $hora = $request->input('hora');
            $useFreeAppointment = $request->input('use_free_appointment') ? true : false;
    
            Log::info('Datos recibidos:', [
                'service_id' => $serviceId,
                'professional_id' => $professionalId,
                'fecha' => $fecha,
                'hora' => $hora,
                'use_free_appointment' => $useFreeAppointment,
            ]);
    
            // Verificar si el intervalo está disponible
            $existingAppointment = Cita::where('cta_profesional_id', $professionalId)
                ->where('cta_fecha', $fecha)
                ->where('cta_hora', $hora)
                ->first();
    
            if ($existingAppointment) {
                Log::info('Intervalo ya reservado', ['cta_id' => $existingAppointment->cta_id]);
                return response()->json(['error' => 'El intervalo seleccionado ya está reservado. Por favor, elige otro.'], 409);
            }
    
            // Asignar el estado correcto
            if ($useFreeAppointment) {
                $estadoId = DB::table('estados_citas')->where('estado_nombre', 'Gratis')->value('estado_id');
                Log::info('Estado "Gratis" obtenido:', ['estado_id' => $estadoId]);
                if (!$estadoId) {
                    Log::error('Estado "Gratis" no encontrado en estados_citas');
                    return response()->json(['error' => 'Estado de cita gratuita no configurado correctamente.'], 500);
                }
            } else {
                $estadoId = DB::table('estados_citas')->where('estado_nombre', 'Pendiente')->value('estado_id');
                Log::info('Estado "Pendiente" obtenido:', ['estado_id' => $estadoId]);
                if (!$estadoId) {
                    Log::error('Estado "Pendiente" no encontrado en estados_citas');
                    return response()->json(['error' => 'Estado de cita pendiente no configurado correctamente.'], 500);
                }
            }
    
            // Crear la cita
            $cita = Cita::create([
                'cta_cliente_id' => $user->usr_id,
                'cta_profesional_id' => $professionalId,
                'cta_fecha' => $fecha,
                'cta_hora' => $hora,
                'cta_estado_id' => $estadoId,
                'cta_is_free' => $useFreeAppointment,
                // Laravel manejará 'cta_created_at' y 'cta_updated_at' automáticamente
            ]);
    
            Log::info('Cita creada exitosamente', ['cta_id' => $cita->cta_id]);
    
            // Asociar el servicio a la cita
            $cita->servicios()->attach($serviceId);
            Log::info('Servicio asociado a la cita', ['servicio_id' => $serviceId]);
    
            // Si la cita es gratuita, actualizar el estado del usuario
            if ($useFreeAppointment) {
                // Verificar si el usuario tiene suficientes puntos
                if ($user->usr_points < 100) {
                    Log::warning('Usuario intenta canjear una cita gratuita sin suficientes puntos', ['user_id' => $user->usr_id]);
                    return response()->json(['error' => 'No tienes suficientes puntos para una cita gratuita.'], 400);
                }
    
                // Deduce 100 puntos del usuario
                $user->usr_points -= 100;
                $user->save();
    
                // Registrar la redención de puntos
                PtsTransaction::create([
                    'pts_usr_id' => $user->usr_id,
                    'pts_type' => 'redeem',
                    'pts_amount' => -100,
                    'pts_description' => 'Redención por Cita Gratuita',
                    'pts_created_at' => now(),
                ]);
    
                // Enviar notificación al usuario (si tienes configurado)
                // $user->notify(new FreeAppointmentUsed($cita));
    
                Log::info('Cita gratuita usada por el usuario', ['user_id' => $user->usr_id, 'cta_id' => $cita->cta_id]);
            }
    
            Log::info('Cita reservada exitosamente');
    
            return response()->json(['success' => 'Tu cita ha sido reservada exitosamente.'], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en saveAppointment: ', $e->errors());
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error en saveAppointment: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }
    
    public function miAgenda()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
    
        // Obtener el nombre del rol del usuario
        $role = $user->role->rol_nombre;

        // Obtener los puntos del usuario
        $userPoints = $user->usr_points;
        
        // Definir los roles permitidos
        $allowedRoles = ['Administrador', 'Barbero', 'Facialista', 'Cliente'];  

        // Determinar si el usuario es un trabajador o un cliente
        $isWorker = in_array($role, ['Administrador', 'Barbero', 'Facialista']);

        if ($isWorker) {
            // Obtener las citas donde el usuario es el profesional
            $citas = Cita::where('cta_profesional_id', $user->usr_id)
                         ->with(['cliente', 'servicios', 'estadoCita'])
                         ->orderBy('cta_fecha', 'desc')
                         ->paginate(10); // Paginación, ajusta el número según tus necesidades
        } else { // Si es cliente
            // Obtener las citas donde el usuario es el cliente
            $citas = Cita::where('cta_cliente_id', $user->usr_id)
                         ->with(['profesional', 'servicios', 'estadoCita'])
                         ->orderBy('cta_fecha', 'desc')
                         ->paginate(10); // Paginación
        }
    
        // Pasar variables adicionales a la vista si es necesario
        return view('appointments.user_appointments', compact('citas', 'isWorker', 'role', 'userPoints'));
    }

    //NUEVOS AGREGADOS
    public function confirm(Cita $cita)
    {
        $user = Auth::user();

        // Verificar que el usuario es el profesional asignado
        if ($cita->cta_profesional_id !== $user->usr_id) {
            return redirect()->route('my-appointments')->with('error', 'No tienes permiso para confirmar esta cita.');
        }

        // Verificar que la cita está en estado 'Pendiente'
        if ($cita->estadoCita->estado_nombre !== 'Pendiente') {
            return redirect()->route('my-appointments')->with('error', 'Solo se pueden confirmar citas pendientes.');
        }

        // Obtener el estado 'Confirmada'
        $estadoConfirmada = DB::table('estados_citas')->where('estado_nombre', 'Confirmada')->value('estado_id');

        if (!$estadoConfirmada) {
            return redirect()->route('my-appointments')->with('error', 'Estado "Confirmada" no configurado correctamente.');
        }

        // Actualizar el estado de la cita
        $cita->cta_estado_id = $estadoConfirmada;
        $cita->save();

        return redirect()->route('my-appointments')->with('success', 'Cita confirmada exitosamente.');
    }

    public function reject(Request $request, Cita $cita)
    {
        // Log de inicio del método
        Log::info('Método reject llamado', [
            'cta_id' => $cita->cta_id,
            'usuario_id' => Auth::id(),
            'estado_actual' => $cita->estadoCita->estado_nombre,
        ]);
    
        $user = Auth::user();
    
        // Verificar que el usuario es el profesional asignado
        if ($cita->cta_profesional_id !== $user->usr_id) {
            Log::warning('Usuario no autorizado para rechazar la cita', [
                'cta_id' => $cita->cta_id,
                'usuario_id' => $user->usr_id,
            ]);
            return redirect()->route('my-appointments')->with('error', 'No tienes permiso para rechazar esta cita.');
        }
    
        // Verificar que la cita está en estado 'Pendiente'
        if ($cita->estadoCita->estado_nombre !== 'Pendiente') {
            Log::warning('Intento de rechazar una cita que no está pendiente', [
                'cta_id' => $cita->cta_id,
                'estado_actual' => $cita->estadoCita->estado_nombre,
                'usuario_id' => $user->usr_id,
            ]);
            return redirect()->route('my-appointments')->with('error', 'Solo se pueden rechazar citas pendientes.');
        }
    
        // Obtener el estado 'Cancelada'
        $estadoCancelada = DB::table('estados_citas')->where('estado_nombre', 'Cancelada')->value('estado_id');
    
        if (!$estadoCancelada) {
            Log::error('Estado "Cancelada" no encontrado en la base de datos', [
                'cta_id' => $cita->cta_id,
                'usuario_id' => $user->usr_id,
            ]);
            return redirect()->route('my-appointments')->with('error', 'Estado "Cancelada" no configurado correctamente.');
        }
    
        // Actualizar el estado de la cita a 'Cancelada' y desactivarla
        $cita->cta_estado_id = $estadoCancelada;
        $cita->cta_activa = false; // Desactivar la cita
        $cita->save();
    
        Log::info('Cita rechazada y desactivada exitosamente', [
            'cta_id' => $cita->cta_id,
            'nuevo_estado' => 'Cancelada',
            'cta_activa' => $cita->cta_activa,
            'usuario_id' => $user->usr_id,
        ]);
    
        return redirect()->route('my-appointments')->with('success', 'Cita rechazada y desactivada exitosamente.');
    }
    
    
}