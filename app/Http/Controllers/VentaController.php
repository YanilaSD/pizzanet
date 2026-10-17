<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VentaController extends Controller
{
    public function index()
    {
        $ventasCompletadas = Venta::where('estado', 1)->count();
        $ventasTotal = Venta::where('estado', 1)->sum('total');
        $descuentoTotal = Venta::where('estado', 1)->sum('descuento');
        $ventasPromedio = Venta::where('estado', 1)->avg('total');
        $ventas = Venta::where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('modules.ventas.index', compact(
            'ventas',
            'ventasCompletadas',
            'ventasTotal',
            'descuentoTotal',
            'ventasPromedio'
        ));
    }

    public function searchClient(Request $request)
    {
        $ci = $request->input('ci');
        $action = $request->input('action');

        if (is_null($ci) && $action === 'buscar') {
            session()->flash(
                'cliente_no_encontrado',
                'Ingrese un CI valido o registre uno nuevo.'
            );

            return redirect()->back();
        }

        Session::forget('productos_venta');

        if ($action === 'sin_cliente') {
            $cliente = Cliente::where('ci', config('app.ci_anonimo'))->first();

            if (! $cliente) {
                session()->flash(
                    'cliente_no_encontrado',
                    'No existe un cliente genérico en la base de datos.'
                );

                return redirect()->back();
            }

            session(['cliente' => $cliente->toArray()]);

            return redirect()->route('ventas.create');
        }

        $cliente = Cliente::where('ci', $ci)->first();

        if (! $cliente) {
            session()->flash(
                'cliente_no_encontrado',
                'No se encontró un cliente con esa CI. ¿Desea registrar este cliente?'
            );

            return redirect()->back();
        }

        session(['cliente' => $cliente->toArray()]);

        return redirect()->route('ventas.create');
    }

    public function create()
    {
        $productos = Producto::where('estado', 1)
            ->with(['inventario', 'categoria'])
            ->orderBy('nombre')
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'categoria' => $producto->categoria?->nombre ?? 'Sin categoría',
                    'precio' => $producto->precio,
                    'stock' => (int) ($producto->inventario?->cantidad ?? 0),
                    'imagen' => $producto->imagen_url,
                ];
            });

        $cliente = Session::get('cliente');

        return view('modules.ventas.create', compact('productos', 'cliente'));
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'tipoPago', 'promocion', 'detalleVentas.producto']);

        return view('modules.ventas.show', compact('venta'));
    }

    public function destroy(Venta $venta)
    {
        $venta->estado = 0;
        $venta->save();

        session()->flash(
            'ventas',
            "Se dio de baja la venta #{$venta->id}."
        );

        return redirect()->route('ventas.index');
    }
}
