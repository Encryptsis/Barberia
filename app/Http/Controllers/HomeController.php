<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la página de inicio.
     */
    public function index()
    {
        return view('home'); // Asegúrate de que la vista se llame 'home.blade.php'
    }
}
