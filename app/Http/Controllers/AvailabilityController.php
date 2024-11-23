<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AvailabilityController extends Controller
{
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
            $workEndTime = '21:00';
            $intervalMinutes = 60; // Intervalos de 1 hora

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $currentTime = $date->copy()->setTimeFromTimeString($workStartTime);
                $endTime = $date->copy()->setTimeFromTimeString($workEndTime);

                while ($currentTime->lt($endTime)) {
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
            Log::error('Error en getAvailableTimes: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }
}
