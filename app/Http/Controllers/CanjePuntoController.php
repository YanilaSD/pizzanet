<?php

namespace App\Http\Controllers;

use App\Models\CanjePunto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CanjePuntoController extends Controller
{
    public function index(Request $request)
    {
        $canjes = CanjePunto::query()
            ->orderBy('estado', 'desc')
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

        return view('modules.canjes.index', compact('canjes'));
    }

    public function create()
    {
        return view('modules.canjes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('canje_puntos', 'nombre'),
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
            'nombre.unique' => 'Ya existe un canje con ese nombre.',
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

        CanjePunto::where('estado', 1)->update(['estado' => 0]);

        CanjePunto::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'puntos' => $validated['puntos'],
            'descuento' => $validated['descuento'],
            'estado' => 1,
        ]);

        return redirect()->route('canjes.index')->with('success', 'Canje creado y activado exitosamente.');
    }

    public function edit(CanjePunto $canje)
    {
        return view('modules.canjes.edit', compact('canje'));
    }

    public function update(Request $request, CanjePunto $canje)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('canje_puntos', 'nombre')->ignore($canje->id),
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
            'nombre.unique' => 'Ya existe otro canje con ese nombre.',
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

        $canje->update($validated);

        return redirect()->route('canjes.index')->with('success', 'Canje actualizado correctamente.');
    }

    public function toggle(CanjePunto $canje)
    {
        if ($canje->estado == 1) {
            if (CanjePunto::where('estado', 1)->count() <= 1) {
                return redirect()
                    ->route('canjes.index')
                    ->with('error', 'Debe existir al menos un canje activo.');
            }

            $canje->update(['estado' => 0]);
        } else {
            DB::transaction(function () use ($canje) {
                CanjePunto::where('estado', 1)
                    ->update(['estado' => 0]);

                $canje->update(['estado' => 1]);
            });
        }

        return redirect()
            ->route('canjes.index')
            ->with('success', 'Canje actualizado correctamente.');
    }
}
