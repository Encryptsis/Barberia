<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosPagosSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados_pagos')->insert([
            ['estado_pago_nombre' => 'Completado', 'estado_pago_descripcion' => 'El pago ha sido realizado exitosamente y ha sido registrado en el sistema'],
            ['estado_pago_nombre' => 'Pendiente', 'estado_pago_descripcion' => 'El pago aún no ha sido realizado o está en proceso de verificación'],
            ['estado_pago_nombre' => 'Fallido', 'estado_pago_descripcion' => 'El intento de pago no fue exitoso debido a un error o rechazo'],
            ['estado_pago_nombre' => 'Reembolsado', 'estado_pago_descripcion' => 'El monto del pago ha sido devuelto al cliente'],
            ['estado_pago_nombre' => 'En Revisión', 'estado_pago_descripcion' => 'El pago está en revisión por posibles inconsistencias o problemas']
        ]);
    }
}
