<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Festividad;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function index(Request $request)
    {
        
        Promocion::sincronizarCompletadas();

        $promociones = Promocion::query()
        ->with('festividad')
        ->conFestividadActiva()
        ->when($request->search, function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('descuento', 'like', "%{$search}%");
            });
        })
        ->paginate(10)
        ->withQueryString();

        return view('modules.promociones.index', compact('promociones'));
    }


    public function create()
    {
        $festividades = Festividad::where('estado', 1)->get();
        return view('modules.promociones.create', compact('festividades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('promociones', 'nombre'),
            ],

            'descuento' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
                'regex:/^\d{1,3}(\.\d{1,2})?$/',
            ],

            'fecha_inicio' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],

            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:' . $request->fecha_inicio,
            ],

            'compra_minima' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'limite_uso' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],

            'festividad_id' => [
                'required',
                'exists:festividades,id',
            ],

        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'Ya existe una promoción con ese nombre.',

            'descuento.required' => 'El descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser numérico.',
            'descuento.min' => 'El descuento no puede ser menor a 0.',
            'descuento.max' => 'El descuento no puede ser mayor a 100.',
            'descuento.regex' => 'El descuento debe tener máximo 2 decimales.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'Debe ser una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',

            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'Debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',

            'compra_minima.numeric' => 'La compra mínima debe ser numérica.',
            'compra_minima.min' => 'La compra mínima no puede ser menor a 0.',
            'compra_minima.regex' => 'La compra mínima debe tener máximo 2 decimales.',

            'limite_uso.integer' => 'El límite de uso debe ser un número entero.',
            'limite_uso.min' => 'El límite de uso debe ser al menos 1.',
            'limite_uso.max' => 'El límite de uso no puede ser mayor a 10000.',

            'festividad_id.required' => 'La festividad es obligatoria.',
            'festividad_id.exists' => 'La festividad seleccionada no es válida.',
        ]);

        Promocion::create([
            'nombre' => $request->nombre,
            'descuento' => $request->descuento,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'compra_minima' => $request->compra_minima,
            'limite_uso' => $request->limite_uso,
            'festividad_id' => $request->festividad_id,
            'estado' => Promocion::resolverEstado(
                $request->fecha_fin,
                $request->filled('limite_uso') ? (int) $request->limite_uso : null
            ),
        ]);

        return redirect()->route('promociones.index')->with('success', 'Promoción creada exitosamente');
    }


    public function edit($id)
    {
        $promocion = Promocion::findOrFail($id);
        $festividades = Festividad::where('estado', 1)->get();
        return view('modules.promociones.edit', compact('promocion', 'festividades'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('promociones', 'nombre')->ignore($id),
            ],

            'descuento' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
                'regex:/^\d{1,3}(\.\d{1,2})?$/',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString('Y-m-d'),
            ],

            'compra_minima' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'limite_uso' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],

            'festividad_id' => [
                'required',
                'exists:festividades,id',
            ],

        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'Ya existe otra promoción con ese nombre.',

            'descuento.required' => 'El descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser numérico.',
            'descuento.min' => 'El descuento no puede ser menor a 0.',
            'descuento.max' => 'El descuento no puede ser mayor a 100.',
            'descuento.regex' => 'El descuento debe tener máximo 2 decimales.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'Debe ser una fecha válida.',

            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'Debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a hoy.',

            'compra_minima.numeric' => 'La compra mínima debe ser numérica.',
            'compra_minima.min' => 'La compra mínima no puede ser menor a 0.',
            'compra_minima.regex' => 'La compra mínima debe tener máximo 2 decimales.',

            'limite_uso.integer' => 'El límite de uso debe ser un número entero.',
            'limite_uso.min' => 'El límite de uso debe ser al menos 1.',
            'limite_uso.max' => 'El límite de uso no puede ser mayor a 10000.',

            'festividad_id.required' => 'La festividad es obligatoria.',
            'festividad_id.exists' => 'La festividad seleccionada no es válida.',
        ]);

        
        $promocion = Promocion::findOrFail($id);

        $estado = (int) $promocion->estado === Promocion::ESTADO_BAJA
            ? Promocion::ESTADO_BAJA
            : Promocion::resolverEstado(
                $request->fecha_fin,
                $request->filled('limite_uso') ? (int) $request->limite_uso : null
            );

        $promocion->update([
            'nombre'        => $request->nombre,
            'descuento'     => $request->descuento,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'compra_minima' => $request->compra_minima,
            'limite_uso'    => $request->limite_uso,
            'festividad_id' => $request->festividad_id,
            'estado'        => $estado,
        ]);


        return redirect()->route('promociones.index')->with('success', 'Promoción actualizada con éxito');
    }

    public function destroy(Promocion $promocion)
    {
        $promocion->update(['estado' => Promocion::ESTADO_BAJA]);
        return redirect()->route('promociones.index')->with('success', 'Promocion eliminada');
    }
}
