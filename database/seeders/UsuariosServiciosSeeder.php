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
            [
                'usr_srv_usuario_id' => 3, // carmen.lopez
                'usr_srv_servicio_id' => 2, // Line Up
                'usr_srv_notas' => 'Coordinación de servicios de alineación de líneas.',
            ],
            [
                'usr_srv_usuario_id' => 4, // antonio.martinez
                'usr_srv_servicio_id' => 4, // Wild Cut
                'usr_srv_notas' => 'Implementar nuevas tendencias en cortes audaces.',
            ],

            // Relaciones para Barberos
            [
                'usr_srv_usuario_id' => 9, // juanita.caballero
                'usr_srv_servicio_id' => 5, // Beer Grooming
                'usr_srv_notas' => 'Especialista en arreglo y cuidado de barba.',
            ],
            [
                'usr_srv_usuario_id' => 9, // juanita.caballero
                'usr_srv_servicio_id' => 4, // Wild Cut
                'usr_srv_notas' => 'Cortes audaces y modernos para clientes exigentes.',
            ],
            [
                'usr_srv_usuario_id' => 10, // pedro.lopez
                'usr_srv_servicio_id' => 7, // Kids
                'usr_srv_notas' => 'Experto en cortes de cabello para niños.',
            ],
            [
                'usr_srv_usuario_id' => 10, // pedro.lopez
                'usr_srv_servicio_id' => 2, // Line Up
                'usr_srv_notas' => 'Definición de líneas para clientes jóvenes.',
            ],
            [
                'usr_srv_usuario_id' => 11, // sofia.ramirez
                'usr_srv_servicio_id' => 6, // Full Cut
                'usr_srv_notas' => 'Cortes completos adaptados a cada cliente.',
            ],
            [
                'usr_srv_usuario_id' => 11, // sofia.ramirez
                'usr_srv_servicio_id' => 4, // Wild Cut
                'usr_srv_notas' => 'Innovación en estilos de corte modernos.',
            ],
            [
                'usr_srv_usuario_id' => 12, // alejandro.molina
                'usr_srv_servicio_id' => 5, // Beer Grooming
                'usr_srv_notas' => 'Cuidado especializado en barba y bigote.',
            ],
            [
                'usr_srv_usuario_id' => 12, // alejandro.molina
                'usr_srv_servicio_id' => 6, // Full Cut
                'usr_srv_notas' => 'Asesoramiento en estilos de corte completo.',
            ],

            // Relaciones para Facialistas
            [
                'usr_srv_usuario_id' => 13, // ana.rodriguez
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Realiza tratamientos faciales personalizados.',
            ],
            [
                'usr_srv_usuario_id' => 13, // ana.rodriguez
                'usr_srv_servicio_id' => 1, // Hydrogen Oxigen
                'usr_srv_notas' => 'Aplicación de tratamientos avanzados de hidratación.',
            ],
            [
                'usr_srv_usuario_id' => 14, // luis.gonzalez
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Especialista en limpieza y exfoliación facial.',
            ],
            [
                'usr_srv_usuario_id' => 14, // luis.gonzalez
                'usr_srv_servicio_id' => 6, // Full Cut
                'usr_srv_notas' => 'Proporciona asesoramiento en cuidado capilar y cortes completos.',
            ],
            [
                'usr_srv_usuario_id' => 15, // laura.blanco
                'usr_srv_servicio_id' => 8, // Classic Haircut
                'usr_srv_notas' => 'Corte clásico adaptado a su estilo personal.',
            ],
            [
                'usr_srv_usuario_id' => 15, // laura.blanco
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Ofrece tratamientos de limpieza facial tradicional.',
            ],
            [
                'usr_srv_usuario_id' => 16, // javier.sanchez
                'usr_srv_servicio_id' => 1, // Hydrogen Oxigen
                'usr_srv_notas' => 'Especialista en tratamientos de hidratación capilar.',
            ],
            [
                'usr_srv_usuario_id' => 16, // javier.sanchez
                'usr_srv_servicio_id' => 3, // Facial
                'usr_srv_notas' => 'Realiza exfoliaciones y limpiezas faciales avanzadas.',
            ],
        ]);
    }
}
