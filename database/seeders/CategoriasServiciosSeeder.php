<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias_servicios')->insert([
            [
                'cat_nombre' => 'Barba',
                'cat_descripcion' => 'Servicios relacionados con el arreglo y cuidado de la barba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_nombre' => 'Cabello',
                'cat_descripcion' => 'Servicios relacionados con el corte y estilo del cabello.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_nombre' => 'Facial',
                'cat_descripcion' => 'Servicios de limpieza y cuidado facial.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade más categorías si es necesario
        ]);
    }
}
