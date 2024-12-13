<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    /**
     * Aplicar una multa al cliente por una cita específica.
     */
    public function chargeFine(Request $request, Cita $cita)
    {
        $user = Auth::user();

        // Calcular el costo total de la cita
        $totalCosto = $cita->servicios->sum('srv_precio'); // Esto te da el costo total en dólares

        // Verificar permisos
        if (!in_array($user->role->rol_nombre, ['Administrador', 'Barbero', 'Facialista'])) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
        }

        // Verificar estado de la cita y si ya se aplicó una multa
        if ($cita->estadoCita->estado_nombre !== 'Confirmada' || $cita->cta_penalty_applied) {
            return response()->json(['error' => 'No se puede aplicar una multa a esta cita.'], 400);
        }

        // Verificar si el cliente tiene un método de pago almacenado
        if (!$cita->cliente->stripe_payment_method_id) {
            return response()->json(['error' => 'El cliente no tiene un método de pago almacenado.'], 400);
        }

        // Configurar la clave secreta de Stripe
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Calcular el 20% de la multa
            $porcentajeMulta = 0.20; // 20%
            $multa = $totalCosto * $porcentajeMulta; // Ejemplo: $50 * 0.20 = $10.00
            $amount = (int) round($multa * 100); // Convertir a centavos: $10.00 -> 1000

            // Crear el PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount, // Usar el monto en centavos
                'currency' => 'usd',
                'customer' => $cita->cliente->stripe_customer_id,
                'payment_method' => $cita->cliente->stripe_payment_method_id,
                'off_session' => true,
                'confirm' => true,
            ]);

            // Actualizar la cita para indicar que se aplicó la multa
            $cita->cta_penalty_applied = true;
            $cita->cta_penalty_amount = $multa; // Guardar en dólares
            $cita->save();

            return response()->json(['success' => 'Multa aplicada exitosamente.']);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json(['error' => 'El pago fue rechazado: ' . $e->getError()->message], 400);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => 'Ocurrió un error con el pago: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }
}
