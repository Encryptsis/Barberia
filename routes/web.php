<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;

// Ruta de inicio (home) accesible para todos
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('agenda/usuario', function () {
        return view('agenda.usuario');
    })->name('agenda.usuario');

    Route::get('perfil/usuario', function () {
        return view('perfil.usuario');
    })->name('perfil.usuario');
});



// Ruta de inicio de sesión (si aún no la tienes definida)
Route::get('/login', [UsuarioController::class, 'loginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout')->middleware('auth');

// Ruta para mostrar el formulario de registro
Route::get('/register', [UsuarioController::class, 'create'])->name('register');

// Ruta para procesar el formulario de registro
Route::post('/register', [UsuarioController::class, 'store'])->name('register.store');

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return '¡Conexión exitosa a la base de datos!';
    } catch (\Exception $e) {
        return 'No se pudo conectar a la base de datos: ' . $e->getMessage();
    }
});
