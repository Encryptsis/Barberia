<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

// Rutas de Administrador
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/AllSchedules', [AgendaController::class, 'index'])->name('AllSchedules.index');
});
