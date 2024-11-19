<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposAccionSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipos_accion')->insert([
            [
                'tipo_accion_nombre' => 'Creación',
                'tipo_accion_descripcion' => 'Acción de creación de registros.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_accion_nombre' => 'Actualización',
                'tipo_accion_descripcion' => 'Acción de actualización de registros.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_accion_nombre' => 'Eliminación',
                'tipo_accion_descripcion' => 'Acción de eliminación de registros.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_accion_nombre' => 'Inicio de Sesión',
                'tipo_accion_descripcion' => 'Acción de inicio de sesión de usuarios.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_accion_nombre' => 'Cierre de Sesión',
                'tipo_accion_descripcion' => 'Acción de cierre de sesión de usuarios.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade otros tipos de acción según tus necesidades
        ]);
    }
}
