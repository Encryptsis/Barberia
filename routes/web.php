<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('agenda/usuario', function () {
    return view('agenda.usuario'); // Asegúrate de que exista la vista en resources/views/agenda/usuario.blade.php
})->name('agenda.usuario');

Route::get('perfil/usuario', function () {
    return view('perfil.usuario'); // Asegúrate de que exista la vista en resources/views/agenda/usuario.blade.php
})->name('perfil.usuario');

Route::get('login', function () {
    return view('auth.login'); // Asegúrate de que exista la vista en resources/views/agenda/usuario.blade.php
})->name('auth.login');

Route::get('register', function () {
    return view('auth.register'); // Asegúrate de que exista la vista en resources/views/agenda/usuario.blade.php
})->name('auth.register');