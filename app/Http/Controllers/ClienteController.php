<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Mostrar los detalles de un cliente.
     *
     * @param  int  $id  Identificador del cliente
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Obtener el usuario actual
        $user = Auth::user();

        // Verificar si el usuario tiene uno de los roles permitidos
        if (!in_array($user->role->rol_nombre, ['Administrador', 'Barbero', 'Facialista'])) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Obtener el cliente por ID
        $cliente = Usuario::findOrFail($id);

        return view('profile.informacion', compact('cliente'));
    }
}
