<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DescuentoController extends Controller
{
    public function index(Request $request)
    {
        $descuentos = Descuento::query()
            ->where('estado', 1)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('nombre', 'like', '%' . $request->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $request->search . '%')
                        ->orWhere('puntos', 'like', '%' . $request->search . '%')
                        ->orWhere('descuento', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('modules.descuentos.index', compact('descuentos'));
    }

    public function create()
    {
        return view('modules.descuentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('descuentos', 'nombre'),
                'regex:/\S/',
            ],
            'descripcion' => [
                'required',
                'string',
                'max:500',
                'regex:/\S/',
            ],
            'puntos' => [
                'required',
                'integer',
                'min:1',
                'max:100000',
            ],
            'descuento' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
                'regex:/^\d{1,6}(\.\d{1,2})?$/',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe un descuento con ese nombre.',
            'nombre.regex' => 'El nombre no puede estar vacío o solo contener espacios.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder 500 caracteres.',
            'descripcion.regex' => 'La descripción no puede estar vacía o solo contener espacios.',
            'puntos.required' => 'Los puntos son obligatorios.',
            'puntos.integer' => 'Los puntos deben ser un número entero.',
            'puntos.min' => 'Los puntos deben ser al menos 1.',
            'puntos.max' => 'Los puntos exceden el límite permitido.',
            'descuento.required' => 'El monto de descuento es obligatorio.',
            'descuento.numeric' => 'El monto de descuento debe ser numérico.',
            'descuento.min' => 'El monto de descuento debe ser mayor a 0.',
            'descuento.max' => 'El monto de descuento excede el límite permitido.',
            'descuento.regex' => 'El monto de descuento debe tener hasta 2 decimales.',
        ]);

        Descuento::where('estado', 1)->update(['estado' => 0]);

        Descuento::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'puntos' => $validated['puntos'],
            'descuento' => $validated['descuento'],
            'estado' => 1,
        ]);

        return redirect()->route('descuentos.index')->with('success', 'Descuento creado y activado exitosamente.');
    }

    public function edit(Descuento $descuento)
    {
        return view('modules.descuentos.edit', compact('descuento'));
    }

    public function update(Request $request, Descuento $descuento)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('descuentos', 'nombre')->ignore($descuento->id),
                'regex:/\S/',
            ],
            'descripcion' => [
                'required',
                'string',
                'max:500',
                'regex:/\S/',
            ],
            'puntos' => [
                'required',
                'integer',
                'min:1',
                'max:100000',
            ],
            'descuento' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
                'regex:/^\d{1,6}(\.\d{1,2})?$/',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe otro descuento con ese nombre.',
            'nombre.regex' => 'El nombre no puede estar vacío o solo contener espacios.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder 500 caracteres.',
            'descripcion.regex' => 'La descripción no puede estar vacía o solo contener espacios.',
            'puntos.required' => 'Los puntos son obligatorios.',
            'puntos.integer' => 'Los puntos deben ser un número entero.',
            'puntos.min' => 'Los puntos deben ser al menos 1.',
            'puntos.max' => 'Los puntos exceden el límite permitido.',
            'descuento.required' => 'El monto de descuento es obligatorio.',
            'descuento.numeric' => 'El monto de descuento debe ser numérico.',
            'descuento.min' => 'El monto de descuento debe ser mayor a 0.',
            'descuento.max' => 'El monto de descuento excede el límite permitido.',
            'descuento.regex' => 'El monto de descuento debe tener hasta 2 decimales.',
        ]);

        $descuento->update($validated);

        return redirect()->route('descuentos.index')->with('success', 'Descuento actualizado correctamente.');
    }

    public function destroy(Descuento $descuento)
    {
        $descuento->update(['estado' => 0]);

        return redirect()->route('descuentos.index')->with('success', 'Descuento dado de baja correctamente.');
    }
}
