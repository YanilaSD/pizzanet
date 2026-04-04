<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Promocion;
use App\Models\DetalleVenta;
use App\Models\TipoPago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventasCompletadas = Venta::where('estado', 1)->count();
        $ventasTotal = Venta::where('estado', 1)->sum('total');
        $descuentoTotal = Venta::where('estado', 1)->sum('descuento');
        $ventasPromedio = Venta::where('estado', 1)->avg('total');
        $ventas = Venta::where('estado', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('modules.ventas.index', compact('ventas', 'ventasCompletadas', 'ventasTotal', 'descuentoTotal', 'ventasPromedio'));
    }

    public function searchClient(Request $request)
    {
        $ci = $request->input('ci');
        $action = $request->input('action');

        if ($action === 'sin_cliente') {
            $cliente = Cliente::where('ci', '77777777')->first();

            if (!$cliente) {
                session()->flash('cliente_no_encontrado', 'No existe un cliente genérico en la base de datos.');
                return redirect()->back();
            }

            session(['cliente' => $cliente->toArray()]);

            return redirect()->route('ventas.create');
        }

        $cliente = Cliente::where('ci', $ci)->first();

        if (!$cliente) {
            session()->flash('cliente_no_encontrado', 'No se encontró un cliente con esa CI. ¿Desea registrar este cliente?');
            return redirect()->back();
        }

        session(['cliente' => $cliente->toArray()]);

        return redirect()->route('ventas.create');
    }



    public function create()
    {

        Session::forget('productos');
        Session::forget('promocion_id');

        $productos_session = session('productos', []);
        $productos = Producto::where('estado', 1)->get();
        $cliente = Session::get('cliente');
        $ventas = Venta::where('estado', 1)->get();
        $tipo_pagos = TipoPago::where('estado', 1)->get();
        return view('modules.ventas.create', compact(
            'productos',
            'ventas',
            'cliente',
            'tipo_pagos',
            'productos_session'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productos = session('productos', []);
        session()->put('productos', $productos);

        $request->validate([
            'tipo_pago_id' => 'required|exists:tipo_pagos,id',
            'cliente_id' => 'required|exists:clientes,id',
            // 'promocion_id' => 'nullable|exists:promociones,id',
        ], [
            'tipo_pago_id.required' => 'El tipo de pago es obligatorio.',
            'tipo_pago_id.exists' => 'El tipo de pago seleccionado no es válido.',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            // 'promocion_id.exists' => 'La promoción seleccionada no es válida.',
        ]);

        $productos = session()->get('productos', []);
        if (empty($productos)) {
            return redirect()->back()
                ->withErrors(['productos' => 'Debe agregar al menos un producto a la venta.'])
                ->withInput();
        }

        $subtotal = array_sum(array_column($productos, 'subtotal'));
        $porcentaje = session('porcentaje_descuento', 0);
        $promocion_id = session('promocion_id', null);
        if (!is_null($promocion_id)) {
            $promocion = Promocion::find($promocion_id);

            if (!$promocion || $promocion->estado != 1) {
                return redirect()->back()
                    ->withErrors(['productos' => 'La promoción no existe o está inactiva.'])
                    ->withInput();
            }

            if (!now()->between($promocion->fecha_inicio, $promocion->fecha_fin)) {
                return redirect()->back()
                    ->withErrors(['productos' => 'La promoción no está vigente.'])
                    ->withInput();
            }

            if ($subtotal < $promocion->compra_minima) {
                return redirect()->back()
                    ->withErrors(['productos' => 'No se alcanza el monto mínimo de compra para esta promoción.'])
                    ->withInput();
            }

            if ($promocion->limite_uso <= 0) {
                return redirect()->back()
                    ->withErrors(['productos' => 'La promoción ha alcanzado su límite de uso.'])
                    ->withInput();
            }

            $promocion->decrement('limite_uso');
        }

        $descuento = ($subtotal * $porcentaje) / 100;
        $total = $subtotal - $descuento;
        $puntos = floor($total / 20);
        $venta = Venta::create([
            'usuario_id' => auth()->user()->id,
            'cliente_id' => $request->cliente_id,
            'tipo_pago_id' => $request->tipo_pago_id,
            'promocion_id' => $promocion_id,
            'puntos' => 0,
            'fecha' => now(),
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'total' => $total,
            'estado' => 1,
        ]);

        $cliente = Cliente::find($request->cliente_id);
        if ($cliente) {
            $descuento = Str::afterLast($request->promocion_id, '-');

            $cliente->puntos += $puntos;
            $cliente->descuento -= $descuento;
            $cliente->save();
        }

        foreach ($productos as $producto) {
            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'subtotal' => $producto['subtotal'],
            ]);
        }

        session()->forget(['productos', 'promocion_id', 'descuento_cliente', 'porcentaje_descuento']);

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Detalle de venta creado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        return view('modules.ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        //
    }
    public function addProducto(Request $request)
    {
        $productoId = $request->input('producto_id');
        $cantidad = (int) $request->input('cantidad');
        $precio = (float) $request->input('precio');

        $producto = Producto::find($productoId);
        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado.'], 404);
        }

        $subtotal = $precio * $cantidad;

        $productos = session()->get('productos', []);

        $productoExistente = false;
        foreach ($productos as &$prod) {
            if ($prod['id'] == $productoId) {
                $prod['cantidad'] = (int)$prod['cantidad'] + $cantidad;
                $prod['subtotal'] = $prod['subtotal'] + $subtotal;
                $productoExistente = true;
                break;
            }
        }

        if (!$productoExistente) {
            $productos[] = [
                'id' => (int)$productoId,
                'nombre' => $producto->nombre,
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'subtotal' => $subtotal,
            ];
        }

        session()->put('productos', $productos);

        $promocionResult = $this->validarPromocion(session('promocion_id', null), $productos);


        return response()->json(['success' => true, 'productos' => $productos]);
    }


    public function getTotalCompra()
    {
        $productos = session()->get('productos', []);
        $total = array_sum(array_column($productos, 'subtotal'));
        $porcentaje = session('porcentaje_descuento', 0);

        $descuento = ($total * $porcentaje) / 100;
        $totalPagar = $total - $descuento;

        return response()->json([
            'total' => $total,
            'descuento' => $descuento,
            'total_pagar' => $totalPagar,
            'porcentaje' => $porcentaje,
        ]);
    }

    public function removeProducto(Request $request)
    {
        $productoId = $request->input('id');
        $productos = session()->get('productos', []);
        $productos = array_filter($productos, function($producto) use ($productoId) {
            return $producto['id'] != $productoId;
        });
        $productos = array_values($productos);
        session()->put('productos', $productos);

        $promocionResult = $this->validarPromocion(session('promocion_id', null), $productos);
        return response()->json(['success' => true, 'productos' => $productos]);
    }

    public function setPromocion(Request $request)
    {
        $promocionId = $request->input('promocion_id');
        $descuentoCliente = $request->input('descuento_cliente');
        $productos = session()->get('productos', []);
        $totalCompra = array_sum(array_column($productos, 'subtotal'));

        session()->forget(['promocion_id', 'descuento_cliente', 'porcentaje_descuento']);

        if ($descuentoCliente) {
            session()->put('porcentaje_descuento', floatval($descuentoCliente));
            return response()->json(['success' => true, 'message' => 'Descuento del cliente aplicado']);
        }

        if ($promocionId) {
            $promocion = Promocion::find($promocionId);

            if (!$promocion || $promocion->estado != 1) {
                return response()->json(['success' => false, 'message' => 'La promoción no es válida.']);
            }

            $hoy = now();
            if ($hoy->lt($promocion->fecha_inicio) || $hoy->gt($promocion->fecha_fin)) {
                return response()->json(['success' => false, 'message' => 'La promoción no está vigente.']);
            }

            if ($totalCompra < $promocion->compra_minima) {
                return response()->json(['success' => false, 'message' => 'Monto insuficiente para promoción.']);
            }

            session()->put('porcentaje_descuento', $promocion->descuento);
            session()->put('promocion_id', $promocion->id);

            return response()->json(['success' => true, 'message' => 'Promoción aplicada correctamente']);
        }

        return response()->json(['success' => true, 'message' => 'Descuento eliminado']);
    }


    protected function validarPromocion($promocionId, $productos)
    {
        $totalCompra = array_sum(array_column($productos, 'subtotal'));

        if (!$promocionId) return ['aplica' => false, 'mensaje' => 'No hay promoción seleccionada'];

        $promocion = Promocion::find($promocionId);

        if (!$promocion || $promocion->estado != 1) {
            return ['aplica' => false, 'mensaje' => 'La promoción no existe o está inactiva'];
        }

        $hoy = Carbon::today();
        if ($hoy->lt(Carbon::parse($promocion->fecha_inicio)) || $hoy->gt(Carbon::parse($promocion->fecha_fin))) {
            return ['aplica' => false, 'mensaje' => 'La promoción no está vigente'];
        }

        if ($promocion->limite_uso <= 0) {
            return ['aplica' => false, 'mensaje' => 'La promoción ha alcanzado su límite de uso'];
        }

        if ($totalCompra < $promocion->compra_minima) {
            return ['aplica' => false, 'mensaje' => 'No alcanza el monto mínimo de compra'];
        }

        $descuento = ($totalCompra * $promocion->descuento) / 100;

        return [
            'aplica' => true,
            'mensaje' => 'Promoción válida',
            'descuento' => $descuento,
            'total' => $totalCompra - $descuento
        ];
    }

    public function getPuntosCliente(Request $request)
    {
        $cliente = Cliente::find($request->cliente_id);

        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado']);
        }

        return response()->json([
            'success' => true,
            'puntos' => $cliente->puntos,
            'equivalente' => ($cliente->puntos / 10) * 20, // ejemplo: 10 pts = 20 Bs
        ]);
    }
    public function setUsoPuntos(Request $request)
    {
        session(['usar_puntos' => $request->usar_puntos]);
        return response()->json(['success' => true]);
    }

}
