<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Cita;

class AdminController extends Controller
{
    /**
     * Mostrar las agendas de los trabajadores.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAgendas()
    {
        // Obtener todos los trabajadores (usuarios con rol 'Cliente' o según corresponda)
        $trabajadores = Usuario::whereHas('role', function($query) {
            $query->where('rol_nombre', 'Cliente');
        })->with(['citas' => function($query) {
            $query->with(['servicios', 'estadoCita']);
        }])->get();

        return view('admin.agendas', compact('trabajadores'));
    }
}
