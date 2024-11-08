<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('agenda/usuario', function () {
    return view('agenda.usuario'); // Asegúrate de que exista la vista en resources/views/agenda/usuario.blade.php
})->name('agenda.usuario');