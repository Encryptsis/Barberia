<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Ejecutar las migraciones de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Definir los roles a insertar
        $roles = [
            [
                'rol_nombre' => 'Administrador',
                'rol_descripcion' => 'Tiene acceso completo a todas las funcionalidades y configuraciones del sistema',
                'rol_nivel' => 1,
                'rol_activo' => true,
            ],
            [
                'rol_nombre' => 'Barbero',
                'rol_descripcion' => 'Encargado de realizar servicios de peluquería y cortes de cabello a los clientes',
                'rol_nivel' => 2,
                'rol_activo' => true,
            ],
            [
                'rol_nombre' => 'Facialista',
                'rol_descripcion' => 'Responsable de realizar tratamientos faciales y cuidados de la piel a los clientes',
                'rol_nivel' => 2,
                'rol_activo' => true,
            ],
            [
                'rol_nombre' => 'Cliente',
                'rol_descripcion' => 'Usuario del sistema que puede solicitar servicios y agendar citas',
                'rol_nivel' => 3,
                'rol_activo' => true,
            ],
        ];

        // Insertar los roles en la base de datos
        DB::table('roles')->insert($roles);
    }
}
