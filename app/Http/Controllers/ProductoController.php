<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->with(['categoria', 'inventario'])
            ->when($request->search, function ($query) use ($request) {
                return $query->where('nombre', 'like', '%' . $request->search . '%')
                             ->orWhere('descripcion', 'like', '%' . $request->search . '%');
            })
            ->orderBy('estado', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('modules.productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::where('estado', 1)->get();
        return view('modules.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => [
                'required',
                'exists:categorias,id',
            ],

            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'nombre'),
            ],

            'descripcion' => [
                'required',
                'string',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'max:10240',
           
            ],

        ], [
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',

            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'No debe exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'Debe ser texto.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'Debe ser numérico.',
            'precio.min' => 'No puede ser negativo.',
            'precio.regex' => 'Formato inválido (máx 2 decimales).',

            'imagen.image' => 'Debe ser una imagen.',
            'imagen.mimes' => 'Solo JPG, JPEG, PNG o GIF.',
            'imagen.max' => 'Máximo 10MB.',
        ]);


        $imagen = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen' => $imagen,
            'estado' => 1,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('modules.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'categoria_id' => [
                'required',
                'exists:categorias,id',
            ],

            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'nombre')->ignore($producto->id),
            ],

            'descripcion' => [
                'required',
                'string',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'max:10240',
            ],

        ], [
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',

            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'No debe exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe otro producto con ese nombre.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'Debe ser texto.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'Debe ser numérico.',
            'precio.min' => 'No puede ser negativo.',
            'precio.regex' => 'Formato inválido (máx 2 decimales).',

            'imagen.image' => 'Debe ser una imagen.',
            'imagen.mimes' => 'Solo JPG, JPEG, PNG o GIF.',
            'imagen.max' => 'Máximo 10MB.',
        ]);

        $imagen = $producto->imagen;
        if ($request->hasFile('imagen')) {
            if ($imagen) {
                Storage::disk('public')->delete($imagen);
            }
            $imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen' => $imagen,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function updateInventario(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => [
                'required',
                'integer',
                'min:0',
            ],
        ], [
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser negativa.',
        ]);

        Inventario::updateOrCreate(
            ['producto_id' => $producto->id],
            ['cantidad' => $request->cantidad, 'estado' => 1]
        );

        return redirect()->route('productos.index')->with('success', 'Inventario actualizado exitosamente.');
    }

    public function toggle(Producto $producto)
    {
        // if ($producto->imagen) {
        //     Storage::disk('public')->delete($producto->imagen);
        // }
        $producto->estado = $producto->estado == 1 ? 0 : 1;
        $producto->save();
        $mensaje = $producto->estado == 1 ? 'Producto habilitado' : 'Producto inhabilitado';

        return redirect()->route('productos.index')->with('success', $mensaje);
    }
}
