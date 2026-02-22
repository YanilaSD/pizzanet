<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Festividad;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $promociones = Promocion::query()
            ->when($request->search, function ($query) use ($request) {
                return $query->where('nombre', 'like', '%' . $request->search . '%')
                            ->orWhere('descuento', 'like', '%' . $request->search . '%');
            })
            ->where('estado', 1)  // Solo promociones activas (estado = 1)
            ->paginate(10);

        return view('modules.promociones.index', compact('promociones'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $festividades = Festividad::where('estado', '1')->get();
        return view('modules.promociones.create', compact('festividades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255', // El nombre es obligatorio, debe ser una cadena de texto y no puede exceder 255 caracteres
            'descuento' => 'required|numeric|min:0|max:100', // El descuento es obligatorio, debe ser numérico y estar entre 0 y 100
            'fecha_inicio' => 'required|date', // La fecha de inicio es obligatoria y debe ser una fecha válida
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio', // La fecha de fin es obligatoria, debe ser una fecha válida y debe ser igual o posterior a la fecha de inicio
            'compra_minima' => 'nullable|numeric|min:0', // La compra mínima es opcional, debe ser numérica y no puede ser menor a 0
            'limite_uso' => 'nullable|integer|min:1', // El límite de uso es opcional, debe ser un número entero y mayor o igual a 1
            'festividad_id' => 'required|exists:festividades,id', // La festividad es obligatoria y debe existir en la tabla de festividades
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',

            'descuento.required' => 'El campo descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser un valor numérico.',
            'descuento.min' => 'El descuento no puede ser menor a 0.',
            'descuento.max' => 'El descuento no puede ser mayor a 100.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',

            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',

            'compra_minima.numeric' => 'La compra mínima debe ser un valor numérico.',
            'compra_minima.min' => 'La compra mínima no puede ser menor a 0.',

            'limite_uso.integer' => 'El límite de uso debe ser un número entero.',
            'limite_uso.min' => 'El límite de uso debe ser al menos 1.',

            'festividad_id.required' => 'La festividad es obligatoria.',
            'festividad_id.exists' => 'La festividad seleccionada no existe.',
        ]);


        // Crear la nueva promoción
        Promocion::create([
            'nombre' => $request->nombre,
            'descuento' => $request->descuento,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'compra_minima' => $request->compra_minima,
            'limite_uso' => $request->limite_uso,
            'festividad_id' => $request->festividad_id,
        ]);

        return redirect()->route('promociones.index')->with('success', 'Promoción creada exitosamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(Promocion $promocion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Obtener la promoción por su ID
        $promocion = Promocion::findOrFail($id);

        // Obtener la lista de festividades para el select
        $festividades = Festividad::all();

        // Devolver la vista con los datos
        return view('modules.promociones.edit', compact('promocion', 'festividades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descuento' => 'required|numeric|min:0|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'compra_minima' => 'nullable|numeric|min:0',
            'limite_uso' => 'nullable|integer|min:1',
            'festividad_id' => 'required|exists:festividades,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'descuento.required' => 'El campo descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser un valor numérico.',
            'descuento.min' => 'El descuento debe ser al menos 0.',
            'descuento.max' => 'El descuento no puede ser mayor que 100.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'compra_minima.numeric' => 'La compra mínima debe ser un valor numérico.',
            'compra_minima.min' => 'La compra mínima debe ser al menos 0.',
            'limite_uso.integer' => 'El límite de uso debe ser un número entero.',
            'limite_uso.min' => 'El límite de uso debe ser al menos 1.',
            'festividad_id.required' => 'La festividad es obligatoria.',
            'festividad_id.exists' => 'La festividad seleccionada no existe.',
        ]);

        // Buscar la promoción a actualizar
        $promocion = Promocion::findOrFail($id);

        // Actualizar los datos de la promoción
        $promocion->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'descuento' => $request->descuento,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'compra_minima' => $request->compra_minima,
            'limite_uso' => $request->limite_uso,
            'festividad_id' => $request->festividad_id,
        ]);

        // Redirigir al índice de promociones con mensaje de éxito
        return redirect()->route('promociones.index')->with('success', 'Promoción actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promocion $promocion)
    {
        $promocion->update(['estado' => 0]);
        return redirect()->route('promociones.index')->with('success', 'Promocion eliminada');
    }
}
