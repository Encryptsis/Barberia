<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentLimitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener los IDs de las categorías
        $categorias = DB::table('categorias_servicios')->pluck('cat_id', 'cat_nombre');

        $barbaId = $categorias['Barba'] ?? null;
        $cabelloId = $categorias['Cabello'] ?? null;
        $facialId = $categorias['Facial'] ?? null;

        DB::table('appointment_limits')->insert([
            [
                'cat_id' => null, // Límite global
                'limite_diario' => 4, // Límite global de citas activas por usuario
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_id' => $barbaId, // Barba
                'limite_diario' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_id' => $cabelloId, // Cabello
                'limite_diario' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_id' => $facialId, // Facial
                'limite_diario' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade más límites según sea necesario
        ]);
    }
}
