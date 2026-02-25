<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Privilegio;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Rol::query()
            ->when($search, fn($query) => $query->where('nombre', 'like', "%{$search}%"))
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('modules.roles.index', compact('roles'));
    }

    public function create()
    {
        // Obtener los privilegios activos ordenados por nombre
        $privilegios = Privilegio::where('estado', '1')->orderBy('nombre')->get();

        // Mapear privilegios para el select: [{value: id, label: nombre}, ...]
        $privilegiosOptions = $privilegios->map(function ($p) {
            return [
                'value' => $p->id,
                'label' => $p->nombre,
            ];
        });

        // Pasar las opciones a la vista (sin rol porque es creación)
        return view('modules.roles.create', compact('privilegiosOptions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.unique' => 'El Nombre ya está registrado.',
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => '1',
        ]);

        $rol->privilegios()->sync($request->privilegios);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function show(Rol $rol)
    {
        $rol->load('privilegios');
        return view('modules.roles.show', compact('rol'));
    }


    public function edit(Rol $rol)
    {
        $privilegiosOptions = Privilegio::all()->map(function ($p) {
            return ['value' => $p->id, 'label' => $p->nombre];
        });

        $privilegiosAsignados = $rol->privilegios()->pluck('privilegios.id')->toArray();

        return view('modules.roles.edit', compact('rol', 'privilegiosOptions', 'privilegiosAsignados'));
    }


    public function update(Request $request, Rol $rol)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre,' . $rol->id, // table en minúsculas
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.unique' => 'El Nombre ya está registrado.',
        ]);

        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        // Sincroniza los privilegios seleccionados (puede ser un array vacío)
        $rol->privilegios()->sync($request->input('privilegios', []));

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }


    public function toggle(Rol $rol)
    {
        $rol->estado = $rol->estado == '1' ? '0' : '1';
        $rol->save();

        return redirect()->route('roles.index')->with('success', 'Estado actualizado correctamente.');
    }
}
