<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosPagosSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados_pagos')->insert([
            [
                'estado_pago_nombre' => 'Completado',
                'estado_pago_descripcion' => 'Pago completado exitosamente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_pago_nombre' => 'Pendiente',
                'estado_pago_descripcion' => 'Pago pendiente de confirmación.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_pago_nombre' => 'Fallido',
                'estado_pago_descripcion' => 'Pago fallido. Intenta nuevamente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_pago_nombre' => 'Reembolsado',
                'estado_pago_descripcion' => 'Pago reembolsado al cliente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade otros estados de pago según tus necesidades
        ]);
    }
}
