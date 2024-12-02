<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            EstadosCitasSeeder::class,
            RolesTableSeeder::class,
            CategoriasServiciosSeeder::class,
            ServiciosSeeder::class,
            AppointmentLimitsSeeder::class,
            TiposAccionSeeder::class,
            MetodosPagoSeeder::class,
            EstadosPagosSeeder::class,
            UsuariosSeeder::class,
            UsuariosServiciosSeeder::class
        ]);
    }
}
