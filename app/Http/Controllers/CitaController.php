<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\EstadoCita;
use App\Models\PunPenalty;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PtsTransaction;
use Illuminate\Support\Facades\Log;


class CitaController extends Controller
{
// CitaController.php

    public function confirmArrival(Request $request, Cita $cita)
    {
        try {
            // Verificar que el usuario es el profesional asignado a la cita
            $user = Auth::user();

            if ($cita->cta_profesional_id !== $user->usr_id) {
                return response()->json(['error' => 'No tienes permiso para confirmar esta cita.'], 403);
            }

            // Validar los datos
            $request->validate([
                'punctuality_status' => 'required|in:on_time,late',
            ]);

            $punctualityStatus = $request->input('punctuality_status');

            // Verificar si la cita ya fue confirmada
            if ($cita->cta_arrival_confirmed) {
                return response()->json(['error' => 'Esta cita ya ha sido confirmada.'], 400);
            }

            // Actualizar la cita
            $cita->cta_arrival_confirmed = true;
            $cita->cta_punctuality_status = $punctualityStatus;
            $cita->cta_arrival_time = now();

            if ($punctualityStatus === 'on_time') {
                // Asignar puntos al cliente
                $cliente = $cita->cliente;
                $cliente->addPoints(10, "Cita completada el {$cita->cta_fecha} a las {$cita->cta_hora}");
            } else if ($punctualityStatus === 'late') {
                // Aplicar penalización (opcional)
                PunPenalty::create([
                    'pun_cta_id' => $cita->cta_id,
                    'pun_usr_id' => $cita->cliente->usr_id,
                    'pun_amount' => 5.00, // Ejemplo de monto de penalización
                    'pun_applied_at' => now(),
                ]);

                // Opcional: restar puntos o notificar al cliente
                // Por ejemplo:
                // $cliente->usr_points = max($cliente->usr_points - 5, 0);
                // $cliente->save();
            }

            $cita->save();

            return response()->json(['success' => 'Llegada confirmada correctamente.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error en confirmArrival: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }


    // Métodos existentes...

    /**
     * Mostrar una lista de las citas del usuario autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('Accediendo a /my-appointments en el método index.');
    
        $user = Auth::user();
        $citas = Cita::where('cta_cliente_id', $user->usr_id)
                     ->with(['servicios', 'profesional', 'estadoCita'])
                     ->orderBy('cta_fecha', 'desc')
                     ->get();
        $userPoints = $user->usr_points;
    
        return view('appointments.citas', compact('citas', 'userPoints'));
    }
    
    

    /**
     * Mostrar el formulario para editar una cita específica.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
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
    
        return view('appointments.edit', compact('cita', 'servicios', 'profesionales'));
    }
    
    

    /**
     * Actualizar una cita específica en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cita $cita)
    {
        $user = Auth::user();
    
        // Verificar si el usuario es el cliente o el profesional asignado
        if ($cita->cta_cliente_id !== $user->usr_id && $cita->cta_profesional_id !== $user->usr_id) {
            return redirect()->route('my.appointments')->with('error', 'No tienes permiso para actualizar esta cita.');
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
    
        return redirect()->route('my.appointments')->with('success', 'Cita actualizada exitosamente.');
    }
    
    

    /**
     * Eliminar una cita específica de la base de datos.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cita $cita)
    {
        $user = Auth::user();

        // Verificar si el usuario es el cliente o el profesional asignado
        if ($cita->cta_cliente_id !== $user->usr_id && $cita->cta_profesional_id !== $user->usr_id) {
            return redirect()->route('my.appointments')->with('error', 'No tienes permiso para eliminar esta cita.');
        }

        $cita->delete();

        return redirect()->route('my.appointments')->with('success', 'Cita eliminada exitosamente.');
    }

    

    // Métodos existentes...

    /**
     * Obtener los profesionales disponibles según el servicio seleccionado.
     *
     * @param  int  $serviceId
     * @return \Illuminate\Http\Response
     */
    /*public function getProfessionals($serviceId)
    {
        // Validar que el servicio existe
        $servicio = Servicio::find($serviceId);
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado.'], 404);
        }

        // Obtener los usuarios (profesionales) que pueden realizar el servicio
        $profesionales = $servicio->usuarios()
            ->where('usuarios.usr_activo', 1)
            ->get(['usuarios.usr_id', 'usuarios.usr_nombre_completo']);

        // Devolver los profesionales en formato JSON
        return response()->json($profesionales);
    }*/

    /**
     * Obtener los tiempos disponibles para un profesional en un rango de fechas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

public function getAvailableTimes(Request $request)
{
    try {
        // Validar los parámetros
        $request->validate([
            'professional_id' => 'required|integer|exists:usuarios,usr_id',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $professionalId = $request->input('professional_id');
        $startDate = Carbon::parse($request->input('start'))->startOfDay();
        $endDate = Carbon::parse($request->input('end'))->endOfDay();

        // Obtener citas existentes dentro del rango de fechas
        $existingAppointments = Cita::where('cta_profesional_id', $professionalId)
            ->whereBetween('cta_fecha', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        // Generar intervalos disponibles
        $availableTimes = [];

        $workStartTime = '11:00';
        $workEndTime = '19:00';
        $intervalMinutes = 60; // Intervalos de 1 hora

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $currentTime = $date->copy()->setTimeFromTimeString($workStartTime);
            $endTime = $date->copy()->setTimeFromTimeString($workEndTime);

            while ($currentTime->lt($endTime)) {
                // Verificar si el intervalo está ocupado
                // Verificar si el intervalo está ocupado
                $isOccupied = $existingAppointments->contains(function ($appointment) use ($currentTime) {
                    return $appointment->cta_fecha === $currentTime->toDateString() &&
                        $appointment->cta_hora === $currentTime->format('H:i:s');
                });


                if (!$isOccupied) {
                    $availableTimes[] = [
                        'title' => '', // Título vacío para evitar texto
                        'start' => $currentTime->toISOString(),
                        'end' => $currentTime->copy()->addMinutes($intervalMinutes)->toISOString(),
                        'backgroundColor' => '#28a745', // Verde más vibrante
                        'borderColor' => '#28a745',
                        'classNames' => ['available-slot'] // Clase personalizada para estilos adicionales si es necesario
                    ];
                }

                $currentTime->addMinutes($intervalMinutes);
            }
        }

        return response()->json($availableTimes);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Error en getAvailableTimes: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno del servidor.'], 500);
    }
}

// app/Http/Controllers/CitaController.php

// app/Http/Controllers/CitaController.php

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
            $estadoId = DB::table('estados_citas')->where('estado_nombre', 'Confirmada')->value('estado_id');
            Log::info('Estado "Confirmada" obtenido:', ['estado_id' => $estadoId]);
            if (!$estadoId) {
                Log::error('Estado "Confirmada" no encontrado en estados_citas');
                return response()->json(['error' => 'Estado de cita confirmada no configurado correctamente.'], 500);
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





// app/Http/Controllers/CitaController.php

public function miAgenda()
{
    // Obtener el usuario autenticado
    $user = Auth::user();

    // Verificar si el usuario tiene un rol que puede tener una agenda
    if (!in_array($user->role->rol_nombre, ['Administrador', 'Barbero', 'Facialista'])) {
        return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta sección.');
    }

    // Obtener las citas donde el usuario es el profesional
    $citas = Cita::where('cta_profesional_id', $user->usr_id)
                 ->with(['cliente', 'servicios', 'estadoCita'])
                 ->orderBy('cta_fecha', 'desc')
                 ->get();

    return view('myagenda.mi_agenda', compact('citas'));
}



}