<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PunPenalty;
use Illuminate\Support\Facades\Log;

class ArrivalController extends Controller
{
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
            }

            $cita->save();

            return response()->json(['success' => 'Llegada confirmada correctamente.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error en confirmArrival: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }
}
