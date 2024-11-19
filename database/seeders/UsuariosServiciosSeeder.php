<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosServiciosSeeder extends Seeder
{
    public function run()
    {
        DB::table('usuarios_servicios')->insert([
            // Relaciones para Administradores
            [
                'usr_srv_usuario_id' => 1, // juan.perez
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Supervisar la calidad de los servicios faciales.',
            ],
            [
                'usr_srv_usuario_id' => 2, // maria.garcia
                'usr_srv_servicio_id' => 1, // Hydrogen Oxigen
                'usr_srv_notas' => 'Gestionar reservas para tratamientos avanzados.',
            ],

            // Relaciones para Clientes
            [
                'usr_srv_usuario_id' => 3, // carlos.sanchez
                'usr_srv_servicio_id' => 6, // Full Cut
                'usr_srv_notas' => 'Prefiere cortes modernos con estilizado adicional.',
            ],
            [
                'usr_srv_usuario_id' => 3, // carlos.sanchez
                'usr_srv_servicio_id' => 2, // Line Up
                'usr_srv_notas' => 'Definición precisa de líneas en barba y cabello.',
            ],
            [
                'usr_srv_usuario_id' => 4, // laura.martin
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Limpieza facial profunda con exfoliación.',
            ],
            [
                'usr_srv_usuario_id' => 4, // laura.martin
                'usr_srv_servicio_id' => 8, // Classic Haircut
                'usr_srv_notas' => 'Corte clásico adaptado a su estilo personal.',
            ],

            // Relaciones para Barberos
            [
                'usr_srv_usuario_id' => 5, // juanita.caballero
                'usr_srv_servicio_id' => 5, // Beer Grooming
                'usr_srv_notas' => 'Especialista en arreglo y cuidado de barba.',
            ],
            [
                'usr_srv_usuario_id' => 5, // juanita.caballero
                'usr_srv_servicio_id' => 4, // Wild Cut
                'usr_srv_notas' => 'Cortes audaces y modernos para clientes exigentes.',
            ],
            [
                'usr_srv_usuario_id' => 6, // pedro.lopez
                'usr_srv_servicio_id' => 7, // Kids
                'usr_srv_notas' => 'Experto en cortes de cabello para niños.',
            ],
            [
                'usr_srv_usuario_id' => 6, // pedro.lopez
                'usr_srv_servicio_id' => 2, // Line Up
                'usr_srv_notas' => 'Definición de líneas para clientes jóvenes.',
            ],

            // Relaciones para Facialistas
            [
                'usr_srv_usuario_id' => 7, // ana.rodriguez
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Realiza tratamientos faciales personalizados.',
            ],
            [
                'usr_srv_usuario_id' => 7, // ana.rodriguez
                'usr_srv_servicio_id' => 1, // Hydrogen Oxigen
                'usr_srv_notas' => 'Aplicación de tratamientos avanzados de hidratación.',
            ],
            [
                'usr_srv_usuario_id' => 8, // luis.gonzalez
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Especialista en limpieza y exfoliación facial.',
            ],
            [
                'usr_srv_usuario_id' => 8, // luis.gonzalez
                'usr_srv_servicio_id' => 6, // Full Cut
                'usr_srv_notas' => 'Proporciona asesoramiento en cuidado capilar y cortes completos.',
            ],
        ]);
    }
}
