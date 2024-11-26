<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\UsuarioController;

// Rutas de Citas (Appointments)
Route::middleware(['auth'])->prefix('citas')->name('appointments.')->group(function () {
    Route::post('/save-appointment', [CitaController::class, 'saveAppointment'])->name('saveAppointment');
    Route::post('/{cita}/confirm-arrival', [ArrivalController::class, 'confirmArrival'])->name('confirmArrival');
    Route::get('/get-professionals/{service_id}', [UsuarioController::class, 'getProfessionals'])->name('getProfessionals');
    Route::get('/get-available-times', [AvailabilityController::class, 'getAvailableTimes'])->name('getAvailableTimes');
});

// Rutas de "Mis Citas"
Route::middleware(['auth'])->group(function () {
    Route::get('/my-appointments', [CitaController::class, 'miAgenda'])->name('my-appointments');
    Route::get('/appointments/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
    Route::put('/appointments/{cita}', [CitaController::class, 'update'])->name('citas.update');
    Route::delete('/appointments/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
});

// Ruta para Agendar una cita de parte del cliente
Route::prefix('BookAnAppointment')->name('agendar.')->group(function () {
    Route::get('/user', [UsuarioController::class, 'agenda'])->name('usuario');
});