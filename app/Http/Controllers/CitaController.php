<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\EstadoCita;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{

    // Métodos existentes...

    /**
     * Mostrar una lista de las citas del usuario autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener las citas del usuario autenticado, ordenadas por fecha descendente
        $citas = Cita::where('cta_cliente_id', Auth::id())
                     ->with(['servicios', 'profesional', 'estadoCita'])
                     ->orderBy('cta_fecha', 'desc')
                     ->get();

        return view('appointments/citas', compact('citas'));
    }

    /**
     * Mostrar el formulario para editar una cita específica.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function edit(Cita $cita)
    {
        // Verificar que la cita pertenece al usuario autenticado
        if ($cita->cta_cliente_id !== Auth::id()) {
            return redirect()->route('my.appointments')->with('error', 'No tienes permiso para editar esta cita.');
        }
    
        // Obtener todos los servicios para el dropdown
        $servicios = Servicio::all();
    
        // Obtener el servicio actual de la cita
        $servicioActual = $cita->servicios->first();
    
        // Obtener los profesionales asociados al servicio actual de la cita usando la relación
        $profesionales = $servicioActual ? $servicioActual->usuarios()->get() : collect();
    
        return view('appointments/edit', compact('cita', 'servicios', 'profesionales'));
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
        // Verificar que la cita pertenece al usuario autenticado
        if ($cita->cta_cliente_id !== Auth::id()) {
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
        // Verificar que la cita pertenece al usuario autenticado
        if ($cita->cta_cliente_id !== Auth::id()) {
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
    public function getProfessionals($serviceId)
    {
        // Obtener los profesionales que ofrecen el servicio especificado
        $profesionales = Usuario::where('srv_id', $serviceId)->get(['usr_id', 'usr_nombre_completo']);

        return response()->json($profesionales);
    }

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


public function saveAppointment(Request $request)
{
    try {
        \Log::info('Entrando en saveAppointment');

        // Verificar autenticación
        $user = auth()->user();
        \Log::info('Usuario autenticado:', ['user_id' => $user ? $user->usr_id : 'null']);

        if (!$user) {
            \Log::warning('Usuario no autenticado en saveAppointment');
            return response()->json(['error' => 'No estás autenticado. Por favor, inicia sesión.'], 401);
        }

        // Validar los datos recibidos
        $request->validate([
            'service_id' => 'required|integer|exists:servicios,srv_id',
            'professional_id' => 'required|integer|exists:usuarios,usr_id',
            'fecha' => 'required|date_format:Y-m-d',
            'hora' => 'required|date_format:H:i:s',
        ]);

        \Log::info('Datos validados correctamente');

        $serviceId = $request->input('service_id');
        $professionalId = $request->input('professional_id');
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');

        // Verificar si el intervalo está disponible
        $existingAppointment = Cita::where('cta_profesional_id', $professionalId)
            ->where('cta_fecha', $fecha)
            ->where('cta_hora', $hora)
            ->first();

        if ($existingAppointment) {
            \Log::info('Intervalo ya reservado', ['cta_id' => $existingAppointment->cta_id]);
            return response()->json(['error' => 'El intervalo seleccionado ya está reservado. Por favor, elige otro.'], 409);
        }

        // Crear la cita
        $cita = Cita::create([
            'cta_cliente_id' => $user->usr_id,
            'cta_profesional_id' => $professionalId,
            'cta_fecha' => $fecha,
            'cta_hora' => $hora,
            'cta_estado_id' => 1,
        ]);

        \Log::info('Cita creada exitosamente', ['cta_id' => $cita->cta_id]);

        // Asociar el servicio a la cita
        $cita->servicios()->attach($serviceId);
        \Log::info('Servicio asociado a la cita', ['servicio_id' => $serviceId]);

        return response()->json(['success' => 'Tu cita ha sido reservada exitosamente.'], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Error de validación en saveAppointment: ', $e->errors());
        return response()->json(['error' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Error en saveAppointment: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
    }
}



    

    
}
