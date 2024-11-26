<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\AvailabilityController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas que son accesibles para todos los usuarios,
| sin importar si están autenticados o no.
|
*/
// Ruta de inicio (home) accesible para todos
Route::get('/', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| Rutas para Invitados
|--------------------------------------------------------------------------
|
| Estas rutas son accesibles solo para usuarios que no están autenticados.
| Incluyen el inicio de sesión y el registro.
|
*/


// Rutas para autenticación y registro (solo para invitados)
Route::middleware(['guest'])->group(function () {
    // Ruta de inicio de sesión
    Route::get('/login', [UsuarioController::class, 'loginForm'])->name('login');
    Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');

    // Ruta para mostrar el formulario de registro
    Route::get('/register', [UsuarioController::class, 'create'])->name('register');

    // Ruta para procesar el formulario de registro
    Route::post('/register', [UsuarioController::class, 'store'])->name('register.store');
});


/*
|--------------------------------------------------------------------------
| Rutas Protegidas por Autenticación
|--------------------------------------------------------------------------
|
| Estas rutas solo son accesibles para usuarios autenticados.
|
*/

Route::middleware(['auth'])->group(function () {
    // Ruta de cierre de sesión
    Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

    /*
    |---------------------------------------------------------------------
    | Rutas de Perfil de Usuario
    |---------------------------------------------------------------------
    */
    Route::prefix('profile')->name('perfil.')->group(function () {
        Route::get('/user', [UsuarioController::class, 'perfil'])->name('usuario');
        Route::post('/user', [UsuarioController::class, 'actualizarPerfil'])->name('actualizar');
    });

    /*
    |---------------------------------------------------------------------
    | Rutas de Agenda de Usuario
    |---------------------------------------------------------------------
    */  
    Route::prefix('BookAnAppointment')->name('agenda.')->group(function () {
        Route::get('/user', [UsuarioController::class, 'agenda'])->name('usuario');
    });
     /*

    |---------------------------------------------------------------------
    | Rutas de Citas (Appointments)
    |---------------------------------------------------------------------
    */   
    Route::prefix('citas')->name('appointments.')->group(function () {
        // Guardar una cita
        Route::post('/save-appointment', [CitaController::class, 'saveAppointment'])->name('saveAppointment');

        // Confirmar llegada a una cita
        Route::post('/{cita}/confirm-arrival', [ArrivalController::class, 'confirmArrival'])->name('confirmArrival');

        // Ruta para obtener profesionales desde CitaController
        Route::get('/get-professionals/{service_id}', [UsuarioController::class, 'getProfessionals'])->name('getProfessionals');

        
        // Obtener tiempos disponibles para citas
        Route::get('/get-available-times', [AvailabilityController::class, 'getAvailableTimes'])->name('getAvailableTimes');
    });
    
    /*
    |---------------------------------------------------------------------
    | Rutas de "Mis Citas"
    |---------------------------------------------------------------------
    */

    // Ruta para ver las citas del usuario
    Route::get('/my-appointments', [CitaController::class, 'miAgenda'])->name('my-appointments');

    // Ruta para mostrar el formulario de edición de una cita
    Route::get('/appointments/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
    
    // Ruta para actualizar una cita existente
    Route::put('/appointments/{cita}', [CitaController::class, 'update'])->name('citas.update');
    
    // Ruta para eliminar una cita
    Route::delete('/appointments/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
    });



/*
|--------------------------------------------------------------------------
| Rutas de Administrador
|--------------------------------------------------------------------------
|
| Estas rutas solo son accesibles para usuarios autenticados y que
| tengan el rol de administrador.
|
*/
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/AllSchedules', [AgendaController::class, 'index'])->name('AllSchedules.index');
});