<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosSeeder extends Seeder
{
    public function run()
    {
        DB::table('servicios')->insert([
            [
                'srv_nombre' => 'Haircut',
                'srv_descripcion' => "Get the haircut you want with our expert stylist. Whether it's a classic style or something unique, just bring a picture, and we'll create the look you desire.",
                'srv_precio' => 45.00,
                'srv_duracion' => '00:40:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Full Cut',
                'srv_descripcion' => "Experience our original full haircut package: A premium grooming service that includes a precise haircut, detailed beard shaping, and eyebrow trimming.",
                'srv_precio' => 60.00,
                'srv_duracion' => '01:00:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Kids',
                'srv_descripcion' => 'We welcome kids for haircuts! For their comfort and safety, we recommend parent and adult supervision for those who are a bit more active.',
                'srv_precio' => 35.00,
                'srv_duracion' => '00:30:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Beard Grooming',
                'srv_descripcion' => 'We offer precise line-ups, shaping, trimming, and shaving. Enjoy a hot towel treatment and relaxing oil for a refreshing experience.',
                'srv_precio' => 30.00,
                'srv_duracion' => '00:30:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Wild Cut',
                'srv_descripcion' => 'Come and live the Wild Deer experience, a service in personal care and well-being, leaving you feeling renewed, confident, and ready for any adventure.',
                'srv_precio' => 115.00,
                'srv_duracion' => '01:30:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Facial',
                'srv_descripcion' => 'We apply masks rich in natural ingredients to deeply nourish and hydrate the skin. This mask, inspired by the purity of nature, returns luminosity and elasticity to your face.',
                'srv_precio' => 35.00,
                'srv_duracion' => '00:30:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Line Up',
                'srv_descripcion' => 'Defining the lines of the forehead, sideburns, and nape, creating a symmetrical and polished finish.',
                'srv_precio' => 40.00,
                'srv_duracion' => '00:30:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ],
            [
                'srv_nombre' => 'Hydrogen Oxygen',
                'srv_descripcion' => 'Is a non-invasive skin care procedure that uses a special device to deliver a mixture of hydrogen gas and oxygen to the skin for deeply cleansing pores and reducing imperfections.',
                'srv_precio' => 140.00,
                'srv_duracion' => '01:00:00',
                'srv_disponible' => true,
                'srv_imagen' => null
            ]
        ]);
    }
}
