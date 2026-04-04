<?php

namespace App\Http\Controllers;

use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = TipoPago::query();

        $tipo_pagos = $query->orderBy('estado', 'desc')
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->withQueryString();

        return view('modules.tipo_pagos.index', compact('tipo_pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.tipo_pagos.create');
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
                'unique:tipo_pagos,nombre',
                'regex:/\S/',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El tipo de pago ya está registrado.',
            'nombre.regex' => 'El nombre no puede estar vacío o solo contener espacios.',
        ]);

        TipoPago::create($request->only('nombre'));
        return redirect()->route('tipo_pagos.index')->with('success', 'Tipo de pago creado exitosamente');
    }

    public function edit(TipoPago $tipo_pago)
    {
        return view('modules.tipo_pagos.edit', compact('tipo_pago'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoPago $tipo_pago)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_pagos', 'nombre')->ignore($tipoPago->id),
                'regex:/\S/',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El tipo de pago ya está registrado.',
            'nombre.regex' => 'El nombre no puede estar vacío o solo contener espacios.',
        ]);

        $tipo_pago->update($request->only('nombre'));
        return redirect()->route('tipo_pagos.index')->with('success', 'Tipo de pago actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function toggle(TipoPago $tipo_pago)
    {
        $tipo_pago->estado = $tipo_pago->estado == 1 ? 0 : 1;
        $tipo_pago->save();
        $mensaje = $tipo_pago->estado == 1 ? 'Tipo de pago activado' : 'Tipo de pago desactivado';

        return redirect()->route('tipo_pagos.index')->with('success', $mensaje);
    }
}
