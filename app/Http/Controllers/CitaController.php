<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\AppointmentLimit;
use Illuminate\Support\Facades\Auth;
use App\Models\PtsTransaction;
use Illuminate\Support\Facades\Log;
use \Carbon\Carbon;

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

        // Si el usuario es un cliente, verificar si faltan menos de 3 horas para la cita
        if ($user->role->rol_nombre === 'Cliente') {
            $appointmentDateTime = Carbon::parse($cita->cta_fecha . ' ' . $cita->cta_hora);
            $now = Carbon::now();
            $diffInHours = $now->floatDiffInHours($appointmentDateTime, false);

            if ($diffInHours <= 3) {
                
                return redirect()->route('mi.agenda')->with('error', 'No puedes editar la cita a menos de 3 horas de su inicio.');
            }
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

     // Si el usuario es un cliente, verificar si faltan menos de 3 horas para la cita
        // Si el usuario es un cliente, verificar si faltan menos de 3 horas para la cita
        if ($user->role->rol_nombre === 'Cliente') {
            $appointmentDateTime = Carbon::parse($cita->cta_fecha . ' ' . $cita->cta_hora);
            $now = Carbon::now();
            $diffInHours = $now->floatDiffInHours($appointmentDateTime, false);

        if ($diffInHours <= 3) {
            return redirect()->route('mi.agenda')->with('error', 'No puedes editar la cita a menos de 3 horas de su inicio.');
        }
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
    
        // Obtener el nuevo servicio y su categoría
        $nuevoServicio = Servicio::find($request->input('service'));
        if (!$nuevoServicio) {
            return redirect()->back()->with('error', 'Servicio no válido.');
        }
        $nuevaCategoriaId = $nuevoServicio->srv_categoria_id;
    
        // Obtener los límites globales y por categoría
        $globalLimit = AppointmentLimit::whereNull('cat_id')->first();
        $categoryLimit = AppointmentLimit::where('cat_id', $nuevaCategoriaId)->first();
    
        if (!$globalLimit || !$categoryLimit) {
            return redirect()->back()->with('error', 'Límites de citas no configurados correctamente.');
        }
    
        // Contar las citas activas globales del usuario excluyendo la cita actual
        $activeGlobalAppointmentsCount = Cita::where('cta_cliente_id', $user->usr_id)
            ->where('cta_activa', true)
            ->where('cta_id', '!=', $cita->cta_id)
            ->count();
    
        // Contar las citas activas por categoría del usuario excluyendo la cita actual
        $activeCategoryAppointmentsCount = Cita::where('cta_cliente_id', $user->usr_id)
            ->where('cta_activa', true)
            ->where('cta_id', '!=', $cita->cta_id)
            ->whereHas('servicios', function($query) use ($nuevaCategoriaId) {
                $query->where('srv_categoria_id', $nuevaCategoriaId);
            })
            ->count();
    
        // Verificar si al actualizar la cita se exceden los límites
        if ($activeGlobalAppointmentsCount >= $globalLimit->limite_diario) {
            return redirect()->back()->with('error', 'Has alcanzado el límite máximo de citas activas.');
        }
    
        if ($activeCategoryAppointmentsCount >= $categoryLimit->limite_diario) {
            return redirect()->back()->with('error', 'Has alcanzado el límite de citas para este servicio.');
        }
    
        // Verificar si el intervalo está disponible (excepto para la cita actual)
        $existingAppointment = Cita::where('cta_profesional_id', $request->input('attendant'))
            ->where('cta_fecha', $request->input('fecha'))
            ->where('cta_hora', $horaConSegundos)
            ->where('cta_activa', true)
            ->where('cta_id', '!=', $cita->cta_id)
            ->first();
    
        if ($existingAppointment) {
            return redirect()->back()->withErrors(['hora' => 'El intervalo seleccionado ya está reservado. Por favor, elige otro.']);
        }
    
        // Actualizar la cita con los nuevos datos
        $cita->cta_profesional_id = $request->input('attendant');
        $cita->cta_fecha = $request->input('fecha');
        $cita->cta_hora = $horaConSegundos;
        
        // Obtener el estado "Pendiente"
        $estadoPendiente = DB::table('estados_citas')->where('estado_nombre', 'Pendiente')->value('estado_id');
        if (!$estadoPendiente) {
            return redirect()->back()->with('error', 'Estado "Pendiente" no configurado correctamente.');
        }
        
        // Cambiar el estado a Pendiente
        $cita->cta_estado_id = $estadoPendiente;

        $cita->save();
    
        // Actualizar los servicios asociados
        $cita->servicios()->sync([$request->input('service')]);
    
        return redirect()->route('my-appointments')->with('success', 'Cita actualizada exitosamente.');
    }

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
    
            // Obtener la categoría del servicio seleccionado
            $servicio = Servicio::find($serviceId);
            if (!$servicio) {
                Log::error('Servicio no encontrado:', ['service_id' => $serviceId]);
                return response()->json(['error' => 'Servicio no válido.'], 400);
            }
            $categoriaId = $servicio->srv_categoria_id;
    
            // Obtener los límites globales y por categoría
            $globalLimit = AppointmentLimit::whereNull('cat_id')->first();
            $categoryLimit = AppointmentLimit::where('cat_id', $categoriaId)->first();
    
            if (!$globalLimit) {
                Log::error('Límite global no configurado en appointment_limits');
                return response()->json(['error' => 'Límite global de citas no configurado correctamente.'], 500);
            }
    
            if (!$categoryLimit) {
                Log::error('Límite por categoría no configurado en appointment_limits', ['cat_id' => $categoriaId]);
                return response()->json(['error' => 'Límite de citas por categoría no configurado correctamente.'], 500);
            }
    
            // Contar las citas activas globales del usuario
            $activeGlobalAppointmentsCount = Cita::where('cta_cliente_id', $user->usr_id)
                ->where('cta_activa', true)
                ->count();
    
            // Contar las citas activas por categoría del usuario
            $activeCategoryAppointmentsCount = Cita::where('cta_cliente_id', $user->usr_id)
                ->where('cta_activa', true)
                ->whereHas('servicios', function($query) use ($categoriaId) {
                    $query->where('srv_categoria_id', $categoriaId);
                })
                ->count();
    
            Log::info('Citas activas globales del usuario:', ['count' => $activeGlobalAppointmentsCount]);
            Log::info('Citas activas por categoría del usuario:', ['count' => $activeCategoryAppointmentsCount]);
    
            // Verificar si el usuario ha alcanzado el límite global
            if ($activeGlobalAppointmentsCount >= $globalLimit->limite_diario) {
                Log::info('Usuario ha alcanzado el límite global de citas activas');
                return response()->json(['error' => 'Has alcanzado el límite máximo de citas activas.'], 429);
            }
    
            // Verificar si el usuario ha alcanzado el límite por categoría
            if ($activeCategoryAppointmentsCount >= $categoryLimit->limite_diario) {
                Log::info('Usuario ha alcanzado el límite de citas para la categoría', ['cat_id' => $categoriaId]);
                return response()->json(['error' => 'Has alcanzado el límite de citas para este servicio.'], 429);
            }
    
            // Verificar si el intervalo está disponible (solo citas activas)
            $existingAppointment = Cita::where('cta_profesional_id', $professionalId)
                ->where('cta_fecha', $fecha)
                ->where('cta_hora', $hora)
                ->where('cta_activa', true)
                ->first();
    
            if ($existingAppointment) {
                Log::info('Intervalo ya reservado', ['cta_id' => $existingAppointment->cta_id]);
                return response()->json(['error' => 'El intervalo seleccionado ya está reservado. Por favor, elige otro.'], 409);
            }
    
            // Asignar el estado correcto
            $estadoId = DB::table('estados_citas')->where('estado_nombre', 'Pendiente')->value('estado_id');
            Log::info('Estado "Pendiente" obtenido:', ['estado_id' => $estadoId]);
            if (!$estadoId) {
                Log::error('Estado "Pendiente" no encontrado en estados_citas');
                return response()->json(['error' => 'Estado de cita pendiente no configurado correctamente.'], 500);
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
                    // Opcional: Eliminar la cita creada si los puntos son insuficientes
                    $cita->delete();
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
    
        // Actualizar el saldo de puntos del usuario
        $user->refresh();
    
        // Obtener el nombre del rol del usuario
        $role = $user->role->rol_nombre;
    
        // Obtener los puntos del usuario
        $userPoints = $user->usr_points;
    
        // Definir los roles permitidos
        $allowedRoles = ['Administrador', 'Barbero', 'Facialista', 'Cliente'];  
    
        // Determinar si el usuario es un trabajador o un cliente
        $isWorker = in_array($role, ['Administrador', 'Barbero', 'Facialista']);
        $isClient = $role === 'Cliente';
    
        // Obtener las citas del usuario
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

        $estadoExpirada = DB::table('estados_citas')->where('estado_nombre', 'Expirada')->value('estado_id');

            // Actualizar las citas expiradas
    foreach ($citas as $cita) {
        if ($cita->cta_activa && 
            !in_array($cita->estadoCita->estado_nombre, ['Cancelada','Completada','Expirada'])) {
            
            // Verificar si la cita ya pasó
            $appointmentTime = Carbon::parse($cita->cta_fecha . ' ' . $cita->cta_hora);
            $now = Carbon::now();

            if ($now->greaterThan($appointmentTime)) {
                // La cita ya pasó y no está cancelada ni completada
                // Cambiamos a estado Expirada y desactivamos
                $cita->cta_estado_id = $estadoExpirada;
                $cita->cta_activa = false;
                $cita->save();
            }
        }
    }
        // Recalcular citas luego de actualizar las expiradas (opcional)
    // Si quieres ver los cambios en la misma carga puedes volver a cargar las citas:
    if ($isWorker) {
        $citas = Cita::where('cta_profesional_id', $user->usr_id)
                     ->with(['cliente', 'servicios', 'estadoCita'])
                     ->orderBy('cta_fecha', 'desc')
                     ->paginate(10);
    } else {
        $citas = Cita::where('cta_cliente_id', $user->usr_id)
                     ->with(['profesional', 'servicios', 'estadoCita'])
                     ->orderBy('cta_fecha', 'desc')
                     ->paginate(10);
    }
        // Obtener los límites globales y por categoría
        $globalLimit = AppointmentLimit::whereNull('cat_id')->first();
        $categoryLimits = AppointmentLimit::whereNotNull('cat_id')->with('categoria_servicio')->get();
    
        // Contar las citas activas globales del usuario
        $activeGlobalAppointmentsCount = Cita::where('cta_cliente_id', $user->usr_id)
            ->where('cta_activa', true)
            ->count();
    
        // Contar las citas activas por categoría del usuario (contando servicios)
        $activeCategoryAppointmentsCount = Cita::where('cta_cliente_id', $user->usr_id)
            ->where('cta_activa', true)
            ->whereHas('servicios', function($query) {
                $query->whereNotNull('srv_categoria_id');
            })
            ->with(['servicios.categoria_servicio'])
            ->get()
            ->flatMap(function ($cita) {
                return $cita->servicios;
            })
            ->groupBy(function ($servicio) {
                return (int) ($servicio->categoria_servicio->cat_id ?? 0);
            })
            ->map(function ($servicios, $catId) {
                return $servicios->count();
            });
    
        // Calcula las citas restantes globales, mínimo 0
        $remainingGlobalAppointments = $globalLimit 
            ? max($globalLimit->limite_diario - $activeGlobalAppointmentsCount, 0) 
            : null;
    
        // Obtener los límites por categoría y keyBy cat_id
        $categoryLimits = AppointmentLimit::whereNotNull('cat_id')
            ->with('categoria_servicio')
            ->get()
            ->keyBy(function($item) {
                return (int)$item->cat_id;
            });

        // Calcula las citas restantes por categoría y asegurar que las claves sean cat_id
        $remainingCategoryAppointments = $categoryLimits->map(function ($limit, $catId) use ($activeCategoryAppointmentsCount) {
            $activeCount = $activeCategoryAppointmentsCount->get((int) $catId, 0);
            return max($limit->limite_diario - $activeCount, 0);
        });

    
        // Añadir logs para depuración
        \Log::info('Usuario ID: ' . $user->usr_id);
        \Log::info('Citas Globales Activas: ' . $activeGlobalAppointmentsCount);
        \Log::info('Citas Restantes Globales: ' . $remainingGlobalAppointments);
    
        foreach ($categoryLimits as $limit) {
            $categoryName = $limit->categoria_servicio->cat_nombre;
            $active = $activeCategoryAppointmentsCount->get((int) $limit->cat_id, 0);
            $remaining = $remainingCategoryAppointments->get((int) $limit->cat_id, 0);
            \Log::info("Categoría: $categoryName | Activas: $active | Restantes: $remaining");
        }
    
        // Pasar variables adicionales a la vista
        return view('appointments.user_appointments', compact(
            'citas',
            'isWorker',
            'role',
            'userPoints',
            'globalLimit',
            'remainingGlobalAppointments',
            'activeGlobalAppointmentsCount', // Añadido
            'categoryLimits',
            'remainingCategoryAppointments',
            'activeCategoryAppointmentsCount' // Añadido
        ));
    }

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
            'action' => $request->input('action'), // Acción: 'reject' o 'cancel'
        ]);
    
        $user = Auth::user();
        $action = $request->input('action');
    
        // Validar que la acción sea válida
        if (!in_array($action, ['reject', 'cancel'])) {
            Log::error('Acción inválida en el método reject', [
                'cta_id' => $cita->cta_id,
                'action' => $action,
                'usuario_id' => $user->usr_id,
            ]);
            return redirect()->route('my-appointments')->with('error', 'Acción inválida.');
        }
    
        if ($action === 'reject') {
            // Solo profesionales asignados pueden rechazar citas
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
        } elseif ($action === 'cancel') {
            // Determinar si el usuario es un cliente o un profesional
            if ($isClient = ($cita->cta_cliente_id === $user->usr_id)) {
                // Cliente puede cancelar sus propias citas en estado 'Pendiente' o 'Confirmada'
                if (!in_array($cita->estadoCita->estado_nombre, ['Pendiente', 'Confirmada'])) {
                    Log::warning('Cliente intentó cancelar una cita que no está pendiente ni confirmada', [
                        'cta_id' => $cita->cta_id,
                        'estado_actual' => $cita->estadoCita->estado_nombre,
                        'usuario_id' => $user->usr_id,
                    ]);
                    return redirect()->route('my-appointments')->with('error', 'Solo puedes cancelar citas pendientes o confirmadas.');
                }
            } elseif ($isWorker = in_array($user->role->rol_nombre, ['Administrador', 'Barbero', 'Facialista'])) {
                // Profesionales pueden cancelar citas en estado 'Confirmada'
                if ($cita->estadoCita->estado_nombre !== 'Confirmada') {
                    Log::warning('Profesional intentó cancelar una cita que no está confirmada', [
                        'cta_id' => $cita->cta_id,
                        'estado_actual' => $cita->estadoCita->estado_nombre,
                        'usuario_id' => $user->usr_id,
                    ]);
                    return redirect()->route('my-appointments')->with('error', 'Solo se pueden cancelar citas confirmadas.');
                }
            } else {
                // Otros roles no autorizados
                Log::warning('Usuario no autorizado para cancelar la cita', [
                    'cta_id' => $cita->cta_id,
                    'usuario_id' => $user->usr_id,
                ]);
                return redirect()->route('my-appointments')->with('error', 'No tienes permiso para cancelar esta cita.');
            }
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
    
        // Si la cita es gratuita, devolver los 100 puntos al cliente
        if ($cita->cta_is_free) {
            $cliente = $cita->cliente;
            if ($cliente) {
                // Devolver los 100 puntos
                $cliente->usr_points += 100;
                $cliente->save();
    
                // Registrar la transacción de puntos
                PtsTransaction::create([
                    'pts_usr_id' => $cliente->usr_id,
                    'pts_type' => 'refund',
                    'pts_amount' => 100,
                    'pts_description' => 'Devolución por cita gratuita cancelada/rechazada',
                    'pts_created_at' => now(),
                ]);
    
                Log::info('Puntos devueltos al cliente por cita gratuita cancelada/rechazada', [
                    'cta_id' => $cita->cta_id,
                    'cliente_id' => $cliente->usr_id,
                    'puntos_devueltos' => 100,
                ]);
            } else {
                Log::warning('No se pudo encontrar al cliente para devolver los puntos', [
                    'cta_id' => $cita->cta_id,
                ]);
            }
        }
    
        // Log de éxito
        Log::info('Cita rechazada/cancelada y desactivada exitosamente', [
            'cta_id' => $cita->cta_id,
            'nuevo_estado' => 'Cancelada',
            'cta_activa' => $cita->cta_activa,
            'usuario_id' => $user->usr_id,
            'action' => $action,
        ]);
    
        // Mensaje de éxito según la acción
        if ($action === 'reject') {
            return redirect()->route('my-appointments')->with('success', 'Cita rechazada y desactivada exitosamente.');
        } elseif ($action === 'cancel') {
            return redirect()->route('my-appointments')->with('success', 'Cita cancelada y desactivada exitosamente.');
        }
    }

    // En app/Http/Controllers/CitaController.php

    public function complete(Request $request, Cita $cita)
    {
        $user = Auth::user();

        // Verificar que el usuario tiene uno de los roles autorizados
        $allowedRoles = ['Administrador', 'Barbero', 'Facialista'];
        if (!in_array($user->role->rol_nombre, $allowedRoles)) {
            return redirect()->route('my-appointments')->with('error', 'No tienes permiso para completar esta cita.');
        }

        // Verificar que la cita está en estado 'Confirmada'
        if ($cita->estadoCita->estado_nombre !== 'Confirmada') {
            return redirect()->route('my-appointments')->with('error', 'Solo se pueden completar citas confirmadas.');
        }

        // Verificar que la llegada del cliente ha sido confirmada
        if (!$cita->cta_arrival_confirmed) {
            return redirect()->route('my-appointments')->with('error', 'No puedes completar una cita sin confirmar la llegada del cliente.');
        }

        // Obtener el estado 'Completada'
        $estadoCompletada = DB::table('estados_citas')->where('estado_nombre', 'Completada')->value('estado_id');

        if (!$estadoCompletada) {
            return redirect()->route('my-appointments')->with('error', 'Estado "Completada" no configurado correctamente.');
        }

        // Actualizar el estado de la cita y desactivarla
        $cita->cta_estado_id = $estadoCompletada;
        $cita->cta_activa = false;
        $cita->save();

        // Opcional: Registrar en el log
        Log::info('Cita completada', [
            'cta_id' => $cita->cta_id,
            'usuario_id' => $user->usr_id,
        ]);

        return redirect()->route('my-appointments')->with('success', 'Cita completada exitosamente.');
    }
    
}