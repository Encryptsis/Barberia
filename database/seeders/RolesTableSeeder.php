<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'rol_nombre' => 'Administrador',
                'rol_descripcion' => 'Usuario con acceso completo al sistema.',
                'rol_nivel' => 1,
                'rol_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rol_nombre' => 'Cliente',
                'rol_descripcion' => 'Usuario que agenda y utiliza los servicios.',
                'rol_nivel' => 2,
                'rol_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rol_nombre' => 'Barbero',
                'rol_descripcion' => 'Profesional que realiza servicios de barbería.',
                'rol_nivel' => 3,
                'rol_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rol_nombre' => 'Facialista',
                'rol_descripcion' => 'Profesional que realiza tratamientos faciales.',
                'rol_nivel' => 3,
                'rol_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade otros roles según tus necesidades
        ]);
    }
}
