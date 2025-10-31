<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $clientes = Cliente::query()
        ->when(!$request->search, function ($query) {
            // Filtra solo los clientes activos (estado = 1) cuando no se está buscando
            $query->where('estado', '1');
        })
        ->when($request->search, function ($query) use ($request) {
            // Cuando hay búsqueda, no aplica filtro por estado
            return $query->where('nombre', 'like', '%' . $request->search . '%')
                        ->orWhere('correo', 'like', '%' . $request->search . '%')
                        ->orWhere('puntos', 'like', '%' . $request->search . '%')
                        ->orWhere('descuento', 'like', '%' . $request->search . '%');
        })
        ->paginate(10); // Paginación de 10 clientes por página


        // Retorna la vista con los clientes paginados
        return view('modules.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:clientes,correo',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe tener más de 255 caracteres.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe ser una dirección de correo electrónico válida.',
            'correo.unique' => 'Este correo ya está registrado en nuestra base de datos.',
        ]);

        // Crear el cliente
        Cliente::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'puntos' => 0,
            'descuento' => 0,
            'estado' => 1
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('modules.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:clientes,correo,' . $id,
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe tener más de 255 caracteres.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe ser una dirección de correo electrónico válida.',
            'correo.unique' => 'Este correo ya está registrado en nuestra base de datos.',
        ]);

        // Encuentra el cliente por su ID
        $cliente = Cliente::findOrFail($id);

        // Actualiza los datos del cliente
        $cliente->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
        ]);

        // Redirige de nuevo con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->update([
            'estado' => 0,
        ]);

        // Redirige de nuevo con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    public function canjear(Request $request, Cliente $cliente)
{
    $request->validate([
        'puntos' => ['required', 'integer', 'min:20'],
    ]);

    $puntos = (int) $request->input('puntos');

    if ($puntos > $cliente->puntos) {
        return back()->withErrors(['puntos' => 'El cliente no tiene suficientes puntos.'])->withInput();
    }

    $bloques = floor($puntos / 40);
    $descuento = $bloques * 10;

    $cliente->puntos -= $puntos;
    $cliente->descuento += $descuento;
    $cliente->save();

    return back()->with('success', "Se canjearon {$puntos} puntos y se añadieron {$descuento}% de descuento.");
}



}
