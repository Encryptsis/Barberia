<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Rutas para autenticación y registro (solo para invitados)
Route::middleware(['guest'])->group(function () {
    // Ruta de inicio de sesión
    Route::get('/login', [UsuarioController::class, 'loginForm'])->name('login');
    // Ruta para procesar el formulario de registro
    Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');

    // Ruta para mostrar el formulario de registro
    Route::get('/register', [UsuarioController::class, 'create'])->name('register');
    // Ruta para procesar el formulario de registro
    Route::post('/register', [UsuarioController::class, 'store'])->name('register.store');
});