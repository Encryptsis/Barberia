<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodosPagoSeeder extends Seeder
{
    public function run()
    {
        DB::table('metodos_pago')->insert([
            ['pago_nombre' => 'Efectivo', 'pago_descripcion' => 'Pago en efectivo realizado directamente en el establecimiento', 'pago_activo' => true],
            ['pago_nombre' => 'Tarjeta de Crédito', 'pago_descripcion' => 'Pago mediante tarjeta de crédito (Visa, MasterCard, etc.)', 'pago_activo' => true],
            ['pago_nombre' => 'Tarjeta de Débito', 'pago_descripcion' => 'Pago mediante tarjeta de débito', 'pago_activo' => true],
            ['pago_nombre' => 'Transferencia Bancaria', 'pago_descripcion' => 'Pago mediante transferencia bancaria desde cualquier entidad financiera', 'pago_activo' => true],
            ['pago_nombre' => 'PayPal', 'pago_descripcion' => 'Pago en línea a través de la plataforma PayPal', 'pago_activo' => true],
            ['pago_nombre' => 'Tarjeta de Regalo', 'pago_descripcion' => 'Pago con tarjeta de regalo del establecimiento', 'pago_activo' => false]
        ]);
    }
}
