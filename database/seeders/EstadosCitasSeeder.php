<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosCitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estados_citas')->insert([
            [
                'estado_nombre' => 'Confirmada',
                'estado_descripcion' => 'La cita ha sido confirmada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_nombre' => 'Cancelada',
                'estado_descripcion' => 'La cita ha sido cancelada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_nombre' => 'Completada',
                'estado_descripcion' => 'La cita ha sido completada exitosamente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_nombre' => 'Pendiente',
                'estado_descripcion' => 'La cita está pendiente de confirmación.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado_nombre' => 'Gratis',
                'estado_descripcion' => 'La cita es gratuita y no requiere costo alguno.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}