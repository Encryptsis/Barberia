<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Cita;

class AgendaController extends Controller
{
    /**
     * Muestra la vista de agendas para el administrador.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener todos los trabajadores (Barberos, Facialistas)
        $workers = Usuario::whereHas('role', function($query){
            $query->whereIn('rol_nombre', ['Barbero', 'Facialista']);
        })->with(['citasProfesional.cliente', 'citasProfesional.servicios', 'citasProfesional.estadoCita'])->get();

        return view('agendas.worker_schedule', compact('workers'));
    }
}
