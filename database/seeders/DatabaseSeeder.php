<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecutar los seeders de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Llamar al seeder de roles
        $this->call(RolesTableSeeder::class);
        
        // Puedes llamar a otros seeders aquí si los tienes
        // $this->call(UsuariosTableSeeder::class);
    }
}
