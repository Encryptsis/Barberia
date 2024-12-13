<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PaymentController;



// Rutas de Citas (Appointments)
Route::middleware(['auth'])->prefix('citas')->name('appointments.')->group(function () {
    Route::post('/save-appointment', [CitaController::class, 'saveAppointment'])->name('saveAppointment');
    Route::post('/{cita}/confirm-arrival', [ArrivalController::class, 'confirmArrival'])->name('confirmArrival');
    Route::get('/get-professionals/{service_id}', [UsuarioController::class, 'getProfessionals'])->name('getProfessionals');
    Route::get('/get-available-times', [AvailabilityController::class, 'getAvailableTimes'])->name('getAvailableTimes');

    // Rutas para confirmar y rechazar citas
    Route::post('/{cita}/confirm', [CitaController::class, 'confirm'])->name('confirm');
    Route::post('/{cita}/reject', [CitaController::class, 'reject'])->name('reject');
    Route::post('/citas/{cita}/complete', [CitaController::class, 'complete'])->name('complete');
});

// Rutas de "Mis Citas"
Route::middleware(['auth'])->group(function () {
    Route::get('/my-appointments', [CitaController::class, 'miAgenda'])->name('my-appointments');
    Route::get('/appointments/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
    Route::put('/appointments/{cita}', [CitaController::class, 'update'])->name('citas.update');
    Route::delete('/appointments/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');


    Route::get('/informacion/{id}', [ClienteController::class, 'show'])->name('informacion.show');

    // Ruta para aplicar la multa
    Route::post('/payment/charge-fine/{cita}', [PaymentController::class, 'chargeFine'])->name('payment.chargeFine');
});

// Ruta para Agendar una cita de parte del cliente
Route::prefix('BookAnAppointment')->name('agendar.')->group(function () {
    Route::get('/user', [UsuarioController::class, 'agenda'])->name('usuario');
});