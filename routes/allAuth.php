<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Ruta de cierre de sesión
    Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

    // Perfil de Usuario
    Route::prefix('profile')->name('perfil.')->group(function () {
        Route::get('/{username}', [UsuarioController::class, 'perfil'])->name('usuario');
        Route::post('/{username}', [UsuarioController::class, 'actualizarPerfil'])->name('actualizar');
    });
});
