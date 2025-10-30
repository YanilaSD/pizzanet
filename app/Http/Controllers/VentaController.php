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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::where('estado', 1)->get();
        $ventas = Venta::where('estado', 1)->get();
        $clientes = Cliente::where('estado', 1)->get();
        $tipo_pagos = TipoPago::where('estado', 1)->get();
        return view('modules.ventas.create', compact('productos', 'ventas', 'clientes', 'tipo_pagos'));
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
            'promocion_id' => 'nullable|exists:promociones,id',
            'cliente_id' => 'required|exists:clientes,id',
        ], [
            'tipo_pago_id.required' => 'El tipo de pago es obligatorio.',
            'tipo_pago_id.exists' => 'El tipo de pago seleccionado no es válido.',
            'promocion_id.exists' => 'La promoción seleccionada no es válida.',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
        ]);

        $productos = session()->get('productos', []);
        $promocion_id = session()->get('promocion_id', null);
        if (empty($productos)) {
            return redirect()->back()->withErrors(['productos' => 'Debe agregar al menos un producto a la venta.'])->withInput();
        }
        $subtotal = 0;
        foreach ($productos as $producto) {
            $subtotal += $producto['subtotal'];
        }

        $descuento = 0;

        if (!is_null($promocion_id)) {
            $promocion = Promocion::find($promocion_id);

            if (!$promocion || $promocion->estado != 1) {
                return redirect()->back()->withErrors(['productos' => 'La promoción no existe o está inactiva.'])->withInput();
            }

            if (!now()->between($promocion->fecha_inicio, $promocion->fecha_fin)) {
                return redirect()->back()->withErrors(['productos' => 'La promoción no está vigente.'])->withInput();
            }

            if ($subtotal < $promocion->compra_minima) {
                return redirect()->back()->withErrors(['productos' => 'No se alcanza el monto mínimo de compra para esta promoción.'])->withInput();
            }

            if ($promocion->limite_uso <= 0) {
                return redirect()->back()->withErrors(['productos' => 'La promoción ha alcanzado su límite de uso.'])->withInput();
            }

            $descuento = ($subtotal * $promocion->descuento) / 100;
            $promocion->limite_uso -= 1;
            $promocion->save();
        } else {
            $descuento = 0;
        }

        $venta = Venta::create([
            'usuario_id' => auth()->user()->id,
            'promocion_id' => $request->promocion_id,
            'cliente_id' => $request->cliente_id,
            'tipo_pago_id' => $request->tipo_pago_id,
            'puntos' => 0,
            'fecha' => Carbon::now(),
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'total' => $total,
            'estado' => 1,
        ]);

        $cliente = Cliente::find($request->cliente_id);
        if ($cliente) {
            $cliente->puntos += $puntos;
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

        session()->forget('productos');
        session()->forget('promocion_id');
        return redirect()->route('ventas.index')->with('success', 'Detalle de venta creado correctamente');
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


    // Método para obtener el total de la compra
    public function getTotalCompra()
    {
        $productos = session()->get('productos', []);
        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['subtotal'];
        }

        $descuento = 0;
        if (session()->has('promocion_id')) {
            $promocion = Promocion::find(session('promocion_id'));
            if ($promocion) {
                $descuento = ($total * $promocion->descuento) / 100;
            }
        }

        $totalPagar = $total - $descuento;
        return response()->json([
            'total' => $total,              // suma de todos los subtotales
            'descuento' => $descuento,      // descuento (si hay)
            'total_pagar' => $totalPagar,   // total final
        ]);
    }


    // Método para eliminar un producto de la sesión
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
            if (!$promocionId) {
            session()->forget('promocion_id');
            return response()->json(['success' => true]);
        }
        $productos = session()->get('productos', []);
        $totalCompra = 0;

        foreach ($productos as $producto) {
            $totalCompra += $producto['subtotal'];
        }

        $promocion = Promocion::find($promocionId);

        if (!$promocion) {
            return response()->json(['success' => false, 'message' => 'La promoción no existe.']);
        }

        if ($promocion->estado != 1) {
            return response()->json(['success' => false, 'message' => 'La promoción está inactiva.']);
        }

        $hoy = Carbon::today();

        if ($hoy->lt(Carbon::parse($promocion->fecha_inicio)) || $hoy->gt(Carbon::parse($promocion->fecha_fin))) {
            return response()->json(['success' => false, 'message' => 'La promoción no está vigente.']);
        }

        if ($promocion->limite_uso <= 0) {
            return response()->json(['success' => false, 'message' => 'La promoción ha alcanzado su límite de uso.']);
        }

        if ($totalCompra < $promocion->compra_minima) {
            return response()->json(['success' => false, 'message' => 'No alcanza el monto mínimo de compra para aplicar esta promoción.']);
        }

        session()->put('promocion_id', $promocion->id);

        return response()->json([
            'success' => true,
            'message' => 'Promoción aplicada correctamente.',
            'promocion' => $promocion,
        ]);
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
