<?php

// Incluir Rutas Generales
require base_path('routes/general.php');

// Incluir Rutas para Invitados
require base_path('routes/guest.php');

// Incluir Rutas Protegidas por Autenticación
require base_path('routes/allAuth.php');

// Incluir Rutas de Citas
require base_path('routes/appointments.php');

// Incluir Rutas de Administrador
require base_path('routes/admin.php');
