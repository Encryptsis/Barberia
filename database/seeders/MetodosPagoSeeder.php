<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodosPagoSeeder extends Seeder
{
    public function run()
    {
        DB::table('metodos_pago')->insert([
            [
                'pago_nombre' => 'Penalty',
                'pago_descripcion' => 'Pago de multa por retraso en la cita.',
                'pago_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pago_nombre' => 'Free Service',
                'pago_descripcion' => 'Servicio gratuito canjeado por puntos de fidelidad.',
                'pago_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pago_nombre' => 'Tarjeta de Crédito',
                'pago_descripcion' => 'Pago mediante tarjeta de crédito.',
                'pago_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pago_nombre' => 'Transferencia Bancaria',
                'pago_descripcion' => 'Pago mediante transferencia bancaria.',
                'pago_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pago_nombre' => 'Efectivo',
                'pago_descripcion' => 'Pago en efectivo.',
                'pago_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade otros métodos de pago según tus necesidades
        ]);
    }
}
