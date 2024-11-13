<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosServiciosSeeder extends Seeder
{
    public function run()
    {
        DB::table('usuarios_servicios')->insert([
            ['usr_srv_usuario_id' => 2, 'usr_srv_servicio_id' => 1, 'usr_srv_notas' => 'Special care for beard grooming'],
            ['usr_srv_usuario_id' => 2, 'usr_srv_servicio_id' => 2, 'usr_srv_notas' => 'Full cut with additional styling'],
            ['usr_srv_usuario_id' => 3, 'usr_srv_servicio_id' => 5, 'usr_srv_notas' => 'Relaxing facial required'],
            ['usr_srv_usuario_id' => 3, 'usr_srv_servicio_id' => 6, 'usr_srv_notas' => 'Hydration and natural skin care'],
            ['usr_srv_usuario_id' => 2, 'usr_srv_servicio_id' => 3, 'usr_srv_notas' => 'Line up for forehead and nape'],
            ['usr_srv_usuario_id' => 3, 'usr_srv_servicio_id' => 4, 'usr_srv_notas' => 'Experience the full Wild Deer grooming'],
        ]);
    }
}
