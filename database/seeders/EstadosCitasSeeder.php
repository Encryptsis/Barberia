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
        DB::table('estados_citas')->insertOrIgnore([
            [
                'estado_nombre' => 'Pendiente',
                'estado_descripcion' => 'La cita ha sido creada y está pendiente de confirmación.',
            ],
            [
                'estado_nombre' => 'Confirmada',
                'estado_descripcion' => 'La cita ha sido confirmada por el profesional.',
            ],
            [
                'estado_nombre' => 'Cancelada',
                'estado_descripcion' => 'La cita ha sido cancelada.',
            ],
            [
                'estado_nombre' => 'Completada',
                'estado_descripcion' => 'La cita se completó satisfactoriamente.',
            ],
        ]);
    }
}
