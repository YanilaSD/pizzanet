<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $usuarios = User::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('modules.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Rol::where('estado', 1)->get();
        return view('modules.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El Nombre debe ser un texto válido.',
            'nombre.max' => 'El Nombre no debe exceder los 255 caracteres.',

            'email.required' => 'El campo Correo es obligatorio.',
            'email.email' => 'El Correo debe tener un formato válido.',
            'email.max' => 'El Correo no debe exceder los 255 caracteres.',
            'email.unique' => 'El Correo ya está registrado, elige otro.',

            'roles.array' => 'Los roles seleccionados no son válidos.',
            'roles.*.exists' => 'Uno de los roles seleccionados no existe.',
        ]);

        // Password temporal (puedes cambiar la lógica)
        // $password = str()->random(10);

        $usuario = User::create([
            'name' => Str::upper($request->nombre),
            'email' => Str::lower($request->lower),
            'password' => Hash::make(12345678),
            'estado' => 1,
        ]);

        // Asignar roles si existen
        if ($request->filled('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::where('estado', 1)->get();
        $rolesAsignados = $usuario->roles()->pluck('roles.id')->toArray();

        return view('modules.usuarios.edit', compact('usuario', 'roles', 'rolesAsignados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ], [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El Nombre debe ser un texto válido.',
            'nombre.max' => 'El Nombre no debe exceder los 255 caracteres.',

            'email.required' => 'El campo Correo es obligatorio.',
            'email.email' => 'El Correo debe tener un formato válido.',
            'email.max' => 'El Correo no debe exceder los 255 caracteres.',
            'email.unique' => 'El Correo ya está registrado, elige otro.',

            'roles.array' => 'Los roles seleccionados no son válidos.',
            'roles.*.exists' => 'Uno de los roles seleccionados no existe.',
        ]);

        $usuario->update([
            'name' => Str::upper($request->nombre),
            'email' => Str::lower($request->email),
        ]);

        $usuario->roles()->sync($request->roles ?? []);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function toggle(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->estado = $usuario->estado == '1' ? '0' : '1';
        $usuario->update();

        return redirect()->route('usuarios.index')->with('success', 'Estado actualizado correctamente.');
    }

    public function reset_pwd(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->password = Hash::make(12345678);
        $usuario->update();

        return redirect()->route('usuarios.index')->with('success', 'Password reseteado correctamente.');
    }
}
