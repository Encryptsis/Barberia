<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Ruta de inicio (home) accesible para todos
Route::get('/', [HomeController::class, 'index'])->name('home');