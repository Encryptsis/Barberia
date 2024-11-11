<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Mostrar una lista de roles.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Mostrar el formulario para crear un nuevo rol.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Almacenar un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rol_nombre' => 'required|unique:roles,rol_nombre|max:50',
            'rol_descripcion' => 'nullable|string',
            'rol_nivel' => 'required|integer',
            'rol_activo' => 'boolean',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Mostrar un rol específico.
     */
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    /**
     * Mostrar el formulario para editar un rol específico.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Actualizar un rol específico en la base de datos.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'rol_nombre' => 'required|unique:roles,rol_nombre,' . $role->rol_id . '|max:50',
            'rol_descripcion' => 'nullable|string',
            'rol_nivel' => 'required|integer',
            'rol_activo' => 'boolean',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Eliminar un rol específico de la base de datos.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
                         ->with('success', 'Rol eliminado exitosamente.');
    }
}
