<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HomeController;

// Ruta de inicio (home) accesible para todos
Route::get('/', [HomeController::class, 'index'])->name('home');


// Rutas protegidas por middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('agenda/usuario', [UsuarioController::class, 'agenda'])->name('agenda.usuario');
    Route::get('perfil/usuario', [UsuarioController::class, 'perfil'])->name('perfil.usuario');
    Route::post('perfil/usuario', [UsuarioController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

    // Nueva ruta para obtener profesionales por servicio
    Route::get('/get-professionals/{service_id}', [UsuarioController::class, 'getProfessionals'])->name('get.professionals');
});

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

// Ruta de prueba de base de datos
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return '¡Conexión exitosa a la base de datos!';
    } catch (\Exception $e) {
        return 'No se pudo conectar a la base de datos: ' . $e->getMessage();
    }
});

Route::get('/get-available-times', [CitaController::class, 'getAvailableTimes'])->name('get.available.times');

