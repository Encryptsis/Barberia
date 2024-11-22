<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Ejecutar los seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            // Usuarios para el rol Administrador
            [
                'usr_username' => 'juan.perez',
                'usr_password' => Hash::make('SecurePass!2024'),
                'usr_nombre_completo' => 'Juan Pérez López',
                'usr_correo_electronico' => 'juan.perez@ejemplo.com',
                'usr_telefono' => '+34 600 123 456',
                'usr_foto_perfil' => 'juan_perez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 1, // ID del rol Administrador
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'maria.garcia',
                'usr_password' => Hash::make('SecurePass!2024'),
                'usr_nombre_completo' => 'María García Fernández',
                'usr_correo_electronico' => 'maria.garcia@ejemplo.com',
                'usr_telefono' => '+34 600 654 321',
                'usr_foto_perfil' => 'maria_garcia.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 1, // ID del rol Administrador
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'carmen.lopez',
                'usr_password' => Hash::make('SecurePass!2024'),
                'usr_nombre_completo' => 'Carmen López Torres',
                'usr_correo_electronico' => 'carmen.lopez@ejemplo.com',
                'usr_telefono' => '+34 600 987 654',
                'usr_foto_perfil' => 'carmen_lopez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 1, // ID del rol Administrador
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'antonio.martinez',
                'usr_password' => Hash::make('SecurePass!2024'),
                'usr_nombre_completo' => 'Antonio Martínez Sánchez',
                'usr_correo_electronico' => 'antonio.martinez@ejemplo.com',
                'usr_telefono' => '+34 600 876 543',
                'usr_foto_perfil' => 'antonio_martinez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 1, // ID del rol Administrador
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],

            // Usuarios para el rol Cliente
            [
                'usr_username' => 'carlos.sanchez',
                'usr_password' => Hash::make('ClientePass!2024'),
                'usr_nombre_completo' => 'Carlos Sánchez Martínez',
                'usr_correo_electronico' => 'carlos.sanchez@cliente.com',
                'usr_telefono' => '+34 611 234 567',
                'usr_foto_perfil' => 'carlos_sanchez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 2, // ID del rol Cliente
                'usr_points' => 50,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'laura.martin',
                'usr_password' => Hash::make('ClientePass!2024'),
                'usr_nombre_completo' => 'Laura Martín Ruiz',
                'usr_correo_electronico' => 'laura.martin@cliente.com',
                'usr_telefono' => '+34 622 345 678',
                'usr_foto_perfil' => 'laura_martin.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 2, // ID del rol Cliente
                'usr_points' => 20,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'elena.diaz',
                'usr_password' => Hash::make('ClientePass!2024'),
                'usr_nombre_completo' => 'Elena Díaz Gómez',
                'usr_correo_electronico' => 'elena.diaz@cliente.com',
                'usr_telefono' => '+34 611 345 678',
                'usr_foto_perfil' => 'elena_diaz.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 2, // ID del rol Cliente
                'usr_points' => 30,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'miguel.navarro',
                'usr_password' => Hash::make('ClientePass!2024'),
                'usr_nombre_completo' => 'Miguel Navarro Ruiz',
                'usr_correo_electronico' => 'miguel.navarro@cliente.com',
                'usr_telefono' => '+34 611 456 789',
                'usr_foto_perfil' => 'miguel_navarro.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 2, // ID del rol Cliente
                'usr_points' => 40,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],

            // Usuarios para el rol Barbero
            [
                'usr_username' => 'juanita.caballero',
                'usr_password' => Hash::make('BarberoPass!2024'),
                'usr_nombre_completo' => 'Juanita Caballero Díaz',
                'usr_correo_electronico' => 'juanita.caballero@barbero.com',
                'usr_telefono' => '+34 633 456 789',
                'usr_foto_perfil' => 'juanita_caballero.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 3, // ID del rol Barbero
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'pedro.lopez',
                'usr_password' => Hash::make('BarberoPass!2024'),
                'usr_nombre_completo' => 'Pedro López García',
                'usr_correo_electronico' => 'pedro.lopez@barbero.com',
                'usr_telefono' => '+34 644 567 890',
                'usr_foto_perfil' => 'pedro_lopez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 3, // ID del rol Barbero
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'sofia.ramirez',
                'usr_password' => Hash::make('BarberoPass!2024'),
                'usr_nombre_completo' => 'Sofía Ramírez Vega',
                'usr_correo_electronico' => 'sofia.ramirez@barbero.com',
                'usr_telefono' => '+34 644 678 901',
                'usr_foto_perfil' => 'sofia_ramirez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 3, // ID del rol Barbero
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'alejandro.molina',
                'usr_password' => Hash::make('BarberoPass!2024'),
                'usr_nombre_completo' => 'Alejandro Molina Torres',
                'usr_correo_electronico' => 'alejandro.molina@barbero.com',
                'usr_telefono' => '+34 644 789 012',
                'usr_foto_perfil' => 'alejandro_molina.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 3, // ID del rol Barbero
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],

            // Usuarios para el rol Facialista
            [
                'usr_username' => 'ana.rodriguez',
                'usr_password' => Hash::make('FacialistaPass!2024'),
                'usr_nombre_completo' => 'Ana Rodríguez Pérez',
                'usr_correo_electronico' => 'ana.rodriguez@facialista.com',
                'usr_telefono' => '+34 655 678 901',
                'usr_foto_perfil' => 'ana_rodriguez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 4, // ID del rol Facialista
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'luis.gonzalez',
                'usr_password' => Hash::make('FacialistaPass!2024'),
                'usr_nombre_completo' => 'Luis González Moreno',
                'usr_correo_electronico' => 'luis.gonzalez@facialista.com',
                'usr_telefono' => '+34 666 789 012',
                'usr_foto_perfil' => 'luis_gonzalez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 4, // ID del rol Facialista
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'laura.blanco',
                'usr_password' => Hash::make('FacialistaPass!2024'),
                'usr_nombre_completo' => 'Laura Blanco López',
                'usr_correo_electronico' => 'laura.blanco@facialista.com',
                'usr_telefono' => '+34 655 890 123',
                'usr_foto_perfil' => 'laura_blanco.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 4, // ID del rol Facialista
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'usr_username' => 'javier.sanchez',
                'usr_password' => Hash::make('FacialistaPass!2024'),
                'usr_nombre_completo' => 'Javier Sánchez Ortiz',
                'usr_correo_electronico' => 'javier.sanchez@facialista.com',
                'usr_telefono' => '+34 655 901 234',
                'usr_foto_perfil' => 'javier_sanchez.jpg',
                'usr_activo' => true,
                'usr_rol_id' => 4, // ID del rol Facialista
                'usr_points' => 0,
                'usr_recuperacion_token' => null,
                'usr_recuperacion_expira' => null,
                'usr_ultimo_acceso' => now(),
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
        ]);
    }
}
