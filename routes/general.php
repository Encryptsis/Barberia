<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

// Ruta de inicio (home) accesible para todos
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/privacy', 'privacy')->name('privacy');

Route::get('/work', function () {
    // Verifica si el usuario está autenticado
    if (Auth::check()) {
        $userRole = Auth::user()->role;

        // Si el rol no es "cliente", redirige
        if (!$userRole || $userRole->rol_nombre !== 'Cliente') {
            return redirect()->route('home')->with('error', 'Access denied');
        }
    }

    // Si el usuario no está autenticado o es cliente, muestra la vista
    return view('work');
})->name('work.index');