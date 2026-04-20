<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
       $clientes = Cliente::query()
        ->when(!$request->search, function ($query) {
            $query->where('estado', 1);
        })
        ->when($request->search, function ($query) use ($request) {
            return $query->where('nombre', 'like', '%' . $request->search . '%')
                        ->orWhere('correo', 'like', '%' . $request->search . '%')
                        ->orWhere('puntos', 'like', '%' . $request->search . '%')
                        ->orWhere('descuento', 'like', '%' . $request->search . '%');
        })
        ->paginate(10);

        return view('modules.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('modules.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ci' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9A-Za-z\s\-]+$/',
                'unique:clientes,ci'
            ],
            'celular' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
                'unique:clientes,celular'
            ],
            'correo' => 'nullable|email|max:255|unique:clientes,correo',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe tener más de 255 caracteres.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.string' => 'El CI debe ser una cadena de texto.',
            'ci.max' => 'El CI no debe tener más de 20 caracteres.',
            'ci.regex' => 'El CI solo puede contener letras, números, espacios y guiones.',
            'ci.unique' => 'Este CI ya está registrado.',
            'celular.required' => 'El celular es obligatorio.',
            'celular.string' => 'El celular debe ser una cadena de texto.',
            'celular.max' => 'El celular no debe tener más de 20 caracteres.',
            'celular.regex' => 'El celular solo puede contener números.',
            'celular.unique' => 'Este celular ya está registrado.',
            'correo.email' => 'El correo debe ser válido.',
            'correo.max' => 'El correo no debe tener más de 255 caracteres.',
            'correo.unique' => 'Este correo ya está registrado.',
        ]);

        Cliente::create([
            'nombre' => Str::upper($request->nombre),
            'correo' => Str::lower($request->correo),
            'ci' => $request->ci,
            'celular' => $request->celular,
            'estado' => 1
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente');
    }

    public function edit(Cliente $cliente)
    {
        return view('modules.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',

            'ci' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9A-Za-z\s\-]+$/',
                Rule::unique('clientes', 'ci')->ignore($id),
            ],

            'celular' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
                Rule::unique('clientes', 'celular')->ignore($id),
            ],

            'correo' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clientes', 'correo')->ignore($id),
            ],

        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe tener más de 255 caracteres.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.string' => 'El CI debe ser una cadena de texto.',
            'ci.max' => 'El CI no debe tener más de 20 caracteres.',
            'ci.regex' => 'El CI solo puede contener letras, números, espacios y guiones.',
            'ci.unique' => 'Este CI ya está registrado.',
            'celular.required' => 'El celular es obligatorio.',
            'celular.string' => 'El celular debe ser una cadena de texto.',
            'celular.max' => 'El celular no debe tener más de 20 caracteres.',
            'celular.regex' => 'El celular solo puede contener números.',
            'celular.unique' => 'Este celular ya está registrado.',
            'correo.email' => 'El correo debe ser válido.',
            'correo.max' => 'El correo no debe tener más de 255 caracteres.',
            'correo.unique' => 'Este correo ya está registrado.',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update([
            'nombre' => Str::upper($request->nombre),
            'correo' => Str::lower($request->correo),
            'ci' => $request->ci,
            'celular' => $request->celular,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');

    }

    public function destroy(Cliente $cliente)
    {
        $cliente->update([
            'estado' => 0,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
