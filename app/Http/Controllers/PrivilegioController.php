<?php

namespace App\Http\Controllers;

use App\Models\Privilegio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Privilegio::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%");
        }

        $privilegios = $query->orderBy('estado', 'desc')
                            ->orderBy('id', 'desc')
                            ->paginate(10)
                            ->withQueryString();

        return view('modules.privilegios.index', compact('privilegios'));
    }

    public function create()
    {
        return view('modules.privilegios.create');
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
                Rule::unique('privilegios', 'nombre'),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('privilegios', 'slug'),
            ],
            'descripcion' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El Nombre debe ser un texto válido.',
            'nombre.max' => 'El Nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El Nombre ya está registrado.',

            'slug.required' => 'El slug es obligatorio.',
            'slug.string' => 'El slug debe ser texto válido.',
            'slug.max' => 'El slug no debe exceder los 255 caracteres.',
            'slug.unique' => 'El slug ya está registrado.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La Descripción debe ser un texto válido.',
            'descripcion.max' => 'La Descripción no debe exceder los 255 caracteres.',
        ]);

        $slug = Str::slug($request->nombre);
        Privilegio::create([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ]);

        return redirect()->route('privilegios.index')->with('success', 'Privilegio creado correctamente.');
    }


    public function edit(Privilegio $privilegio)
    {
        return view('modules.privilegios.edit', compact('privilegio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Privilegio $privilegio)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('privilegios', 'nombre')->ignore($privilegio->id),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('privilegios', 'slug')->ignore($privilegio->id),
            ],
            'descripcion' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El Nombre debe ser un texto válido.',
            'nombre.max' => 'El Nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El Nombre ya está registrado.',

            'slug.required' => 'El slug es obligatorio.',
            'slug.string' => 'El slug debe ser texto válido.',
            'slug.max' => 'El slug no debe exceder los 255 caracteres.',
            'slug.unique' => 'El slug ya está registrado.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La Descripción debe ser un texto válido.',
            'descripcion.max' => 'La Descripción no debe exceder los 255 caracteres.',
        ]);


        $slug = Str::slug($request->nombre);
        $privilegio->update([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('privilegios.index')->with('success', 'Privilegio actualizado correctamente.');
    }

    public function toggle($id)
    {
        $privilegio = Privilegio::findOrFail($id);
        $privilegio->estado = $privilegio->estado == 1 ? 0 : 1;
        $privilegio->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Privilegio $privilegio)
    {
        //
    }
}
