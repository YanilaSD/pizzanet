<?php

namespace App\Http\Controllers;

use App\Models\Festividad;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class FestividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $festividades = Festividad::query()
        ->where('estado', 1)
        ->when($request->search, function ($query) use ($request) {
            return $query->where('nombre', 'like', '%' . $request->search . '%')
                         ->orWhere('descripcion', 'like', '%' . $request->search . '%');
        })
        ->paginate(10);

        return view('modules.festividades.index', compact('festividades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.festividades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('festividades', 'nombre'),
            ],
            'descripcion' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string'   => 'El nombre debe ser una cadena de texto.',
            'nombre.max'      => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique'   => 'Ya existe un registro con ese nombre.',

            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string'   => 'La descripción debe ser una cadena de texto.',
            'descripcion.max'      => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        Festividad::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('festividades.index')->with('success', 'Festividad creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Festividad $festividad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Festividad $festividad)
    {
        return view('modules.festividades.edit', compact('festividad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Festividad $festividad)
    {

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('festividades', 'nombre')->ignore($festividad->id),
            ],
            'descripcion' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique' => 'Ya existe otra festividad con ese nombre.',

            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        $festividad->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('festividades.index')->with('success', 'Festividad actualizada con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Festividad $festividad)
    {
        $festividad->update(['estado' => 0]);

        return redirect()->route('festividades.index')->with('success', 'Festividad eliminada.');
    }
}
