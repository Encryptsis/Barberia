<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosSeeder extends Seeder
{
    public function run()
    {
        // Obtener las categorías existentes y mapear sus nombres a sus IDs
        $categorias = DB::table('categorias_servicios')->pluck('cat_id', 'cat_nombre');

        // Verificar que las categorías necesarias existen
        $categoriasNecesarias = ['Barba', 'Cabello', 'Facial'];

        foreach ($categoriasNecesarias as $categoriaNombre) {
            if (!isset($categorias[$categoriaNombre])) {
                throw new \Exception("La categoría '{$categoriaNombre}' no existe en la tabla 'categorias_servicios'. Asegúrate de que el seeder 'CategoriasServiciosSeeder' se haya ejecutado correctamente.");
            }
        }

        // Asignar IDs de categorías a variables para facilitar la asignación
        $barbaId = $categorias['Barba'];
        $cabelloId = $categorias['Cabello'];
        $facialId = $categorias['Facial'];

        // Insertar los servicios con la asociación de categorías
        DB::table('servicios')->insert([
            [
                'srv_nombre' => 'Hydrogen Oxigen',
                'srv_descripcion' => 'Tratamiento avanzado para el cabello con oxígeno e hidrógeno.',
                'srv_precio' => 140.00,
                'srv_duracion' => '01:30:00',
                'srv_disponible' => true,
                'srv_imagen' => 'hydrogen_oxigen.jpg',
                'srv_categoria_id' => $cabelloId, // Categoría: Cabello
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Line Up',
                'srv_descripcion' => 'Definición de líneas en el cabello y barba.',
                'srv_precio' => 40.00,
                'srv_duracion' => '00:30:00',
                'srv_disponible' => true,
                'srv_imagen' => 'line_up.jpg',
                'srv_categoria_id' => $barbaId, // Categoría: Barba
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Facial',
                'srv_descripcion' => 'Limpieza facial profunda y exfoliación.',
                'srv_precio' => 55.00,
                'srv_duracion' => '01:00:00',
                'srv_disponible' => true,
                'srv_imagen' => 'facial.jpg',
                'srv_categoria_id' => $facialId, // Categoría: Facial
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Wild Cut',
                'srv_descripcion' => 'Corte de cabello con estilo moderno y audaz.',
                'srv_precio' => 115.00,
                'srv_duracion' => '01:15:00',
                'srv_disponible' => true,
                'srv_imagen' => 'wild_cut.jpg',
                'srv_categoria_id' => $cabelloId, // Categoría: Cabello
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Beer Grooming',
                'srv_descripcion' => 'Arreglo y cuidado de la barba.',
                'srv_precio' => 30.00,
                'srv_duracion' => '00:45:00',
                'srv_disponible' => true,
                'srv_imagen' => 'beer_grooming.jpg',
                'srv_categoria_id' => $barbaId, // Categoría: Barba
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Full Cut',
                'srv_descripcion' => 'Corte completo para cabello y barba.',
                'srv_precio' => 60.00,
                'srv_duracion' => '01:00:00',
                'srv_disponible' => true,
                'srv_imagen' => 'full_cut.jpg',
                'srv_categoria_id' => $cabelloId, // Categoría: Cabello
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Kids',
                'srv_descripcion' => 'Corte de cabello para niños.',
                'srv_precio' => 35.00,
                'srv_duracion' => '00:30:00',
                'srv_disponible' => true,
                'srv_imagen' => 'kids.jpg',
                'srv_categoria_id' => $cabelloId, // Categoría: Cabello
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'srv_nombre' => 'Classic Haircut',
                'srv_descripcion' => 'Corte de cabello clásico y tradicional.',
                'srv_precio' => 35.00,
                'srv_duracion' => '00:45:00',
                'srv_disponible' => true,
                'srv_imagen' => 'classic_haircut.jpg',
                'srv_categoria_id' => $cabelloId, // Categoría: Cabello
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
