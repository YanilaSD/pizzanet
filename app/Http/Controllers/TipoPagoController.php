<?php

namespace App\Http\Controllers;

use App\Models\TipoPago;
use Illuminate\Http\Request;

class TipoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipo_pagos = TipoPago::paginate(10);  // Obtener todos los tipos de pago
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
        'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
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
        'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
        ]);

        $tipo_pago->update($request->only('nombre'));  // Actualizar tipo de pago
        return redirect()->route('tipo_pagos.index')->with('success', 'Tipo de pago actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoPago $tipo_pago)
    {
        $tipo_pago->update(['status' => 0]);
        return redirect()->route('tipo_pagos.index')->with('success', 'Tipo de pago desactivado');
    }
}
