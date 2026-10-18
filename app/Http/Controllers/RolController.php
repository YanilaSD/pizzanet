<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Privilegio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $privilegios = Privilegio::where('estado', 1)->orderBy('nombre')->get();
        $privilegiosOptions = $privilegios->map(function ($p) {
            return [
                'value' => $p->id,
                'label' => $p->nombre,
            ];
        });

        return view('modules.roles.create', compact('privilegiosOptions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:roles,nombre',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:255',
                'required',
            ],

        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El nombre ya está registrado.',

            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder los 255 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => 1,
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
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'nombre')->ignore($rol->id),
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:255',
                'required',
            ],

        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El nombre ya está registrado.',

            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder los 255 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

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
