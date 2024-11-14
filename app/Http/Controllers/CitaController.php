<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;

class CitaController extends Controller
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
            $start = $request->input('start');
            $end = $request->input('end');

            // Parsear las fechas
            $startDate = Carbon::createFromFormat('Y-m-d\TH:i:sP', $start);
            $endDate = Carbon::createFromFormat('Y-m-d\TH:i:sP', $end);


            // Obtener citas existentes
            $existingAppointments = Cita::where('cta_profesional_id', $professionalId)
                ->whereBetween('cta_fecha_hora', [$startDate, $endDate])
                ->get();

            // Generar intervalos disponibles
            $availableTimes = [];

            $workStartTime = '11:00';
            $workEndTime = '20:00';
            $intervalMinutes = 30;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                if (!$date->isWeekday()) {
                    continue;
                }

                $currentTime = $date->copy()->setTimeFromTimeString($workStartTime);
                $endTime = $date->copy()->setTimeFromTimeString($workEndTime);

                while ($currentTime->lt($endTime)) {
                    $isOccupied = $existingAppointments->contains(function ($appointment) use ($currentTime) {
                        return Carbon::parse($appointment->cta_fecha_hora)->equalTo($currentTime);
                    });

                    if (!$isOccupied) {
                        $availableTimes[] = [
                            'title' => 'Disponible',
                            'start' => $currentTime->toDateTimeString(),
                            'end' => $currentTime->copy()->addMinutes($intervalMinutes)->toDateTimeString(),
                            'backgroundColor' => 'green',
                            'borderColor' => 'green',
                        ];
                    }

                    $currentTime->addMinutes($intervalMinutes);
                }
            }

            return response()->json($availableTimes);

        } catch (\Exception $e) {
            \Log::error('Error en getAvailableTimes: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
