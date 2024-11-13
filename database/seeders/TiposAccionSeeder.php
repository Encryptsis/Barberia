<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposAccionSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipos_accion')->insert([
            ['tipo_accion_nombre' => 'Creación de Usuario', 'tipo_accion_descripcion' => 'Registro de un nuevo usuario en el sistema'],
            ['tipo_accion_nombre' => 'Actualización de Usuario', 'tipo_accion_descripcion' => 'Modificación de la información de un usuario existente'],
            ['tipo_accion_nombre' => 'Eliminación de Usuario', 'tipo_accion_descripcion' => 'Eliminación de un usuario del sistema'],
            ['tipo_accion_nombre' => 'Creación de Cita', 'tipo_accion_descripcion' => 'Programación de una nueva cita para un cliente'],
            ['tipo_accion_nombre' => 'Actualización de Cita', 'tipo_accion_descripcion' => 'Modificación de los detalles de una cita existente'],
            ['tipo_accion_nombre' => 'Cancelación de Cita', 'tipo_accion_descripcion' => 'Cancelación de una cita previamente programada'],
            ['tipo_accion_nombre' => 'Creación de Pago', 'tipo_accion_descripcion' => 'Registro de un nuevo pago en el sistema'],
            ['tipo_accion_nombre' => 'Actualización de Pago', 'tipo_accion_descripcion' => 'Modificación de la información de un pago existente'],
            ['tipo_accion_nombre' => 'Eliminación de Pago', 'tipo_accion_descripcion' => 'Eliminación de un registro de pago del sistema'],
            ['tipo_accion_nombre' => 'Inicio de Sesión', 'tipo_accion_descripcion' => 'Registro de un usuario iniciando sesión en el sistema'],
            ['tipo_accion_nombre' => 'Cierre de Sesión', 'tipo_accion_descripcion' => 'Registro de un usuario cerrando sesión en el sistema']
        ]);
    }
}
