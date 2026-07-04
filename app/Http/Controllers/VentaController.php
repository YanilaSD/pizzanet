<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Promocion;
use App\Models\DetalleVenta;
use App\Models\HistorialCanje;
use App\Models\Descuento;
use App\Models\TipoPago;
use Carbon\Carbon;
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
        $ventas = Venta::where('estado', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('modules.ventas.index', compact('ventas', 'ventasCompletadas', 'ventasTotal', 'descuentoTotal', 'ventasPromedio'));
    }

    public function searchClient(Request $request)
    {
        $ci = $request->input('ci');
        $action = $request->input('action');

        if (is_null($ci) && $action == 'buscar') {
            session()->flash('cliente_no_encontrado', 'Ingrese un CI valido o registre uno nuevo.');
            return redirect()->back();
        }

        if ($action === 'sin_cliente') {
            $cliente = Cliente::where('ci', config('app.ci_anonimo'))->first();

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
        Session::forget(['productos', 'promocion_id', 'porcentaje_descuento', 'usar_puntos']);

        $_productos        = Producto::where('estado', 1)->with('inventario')->get();
        $cliente          = Session::get('cliente');
        $tipo_pagos       = TipoPago::where('estado', 1)->get();
        $productos_session = session('productos', []);
        $productos = $_productos->map(function ($producto) {
            return [
                'id'        => $producto->id,
                'nombre'    => $producto->nombre,
                'categoria' => $producto->categoria->nombre,
                'precio'    => $producto->precio,
                'stock'  => $producto->inventario?->cantidad ?? 0,
                'imagen'    => $producto->imagen_url,
            ];
        });
        return view('modules.ventas.create', compact(
            'productos', 'cliente', 'tipo_pagos', 'productos_session'
        ));
    }

    public function show(Venta $venta)
    {
        return view('modules.ventas.show', compact('venta'));
    }

    public function addProducto(Request $request)
    {
        $productoId = $request->input('producto_id');
        $cantidad   = (int) $request->input('cantidad');
        $precio     = (float) $request->input('precio');
        $producto   = Producto::find($productoId);

        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado.'], 404);
        }

        $productos = session()->get('productos', []);
        $existe    = false;

        foreach ($productos as &$prod) {
            if ($prod['id'] == $productoId) {
                $prod['cantidad'] += $cantidad;
                $prod['subtotal'] += $precio * $cantidad;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $productos[] = [
                'id'             => (int) $productoId,
                'nombre'         => $producto->nombre,
                'cantidad'       => $cantidad,
                'precio_unitario'=> $precio,
                'subtotal'       => $precio * $cantidad,
            ];
        }

        session()->put('productos', $productos);
        return response()->json(['success' => true, 'productos' => $productos]);
    }

    public function removeProducto(Request $request)
    {
        $productoId = $request->input('id');
        $productos  = session()->get('productos', []);
        $productos  = array_values(array_filter($productos, fn($p) => $p['id'] != $productoId));
        session()->put('productos', $productos);

        return response()->json(['success' => true, 'productos' => $productos]);
    }

    public function getTotalCompra()
    {
        $productos  = session()->get('productos', []);
        $subtotal   = array_sum(array_column($productos, 'subtotal'));
        $porcentaje = session('porcentaje_descuento', 0);
        $descuento  = ($subtotal * $porcentaje) / 100;

        return response()->json([
            'total'      => $subtotal,
            'descuento'  => $descuento,
            'total_pagar'=> $subtotal - $descuento,
            'porcentaje' => $porcentaje,
        ]);
    }

    public function setPromocion(Request $request)
    {
        $promocionId = $request->input('promocion_id');
        $productos   = session()->get('productos', []);
        $totalCompra = array_sum(array_column($productos, 'subtotal'));

        session()->forget(['promocion_id', 'porcentaje_descuento']);

        if (!$promocionId) {
            return response()->json(['success' => true, 'message' => 'Descuento eliminado']);
        }

        $promocion = Promocion::find($promocionId);

        if (!$promocion || $promocion->estado != 1) {
            return response()->json(['success' => false, 'message' => 'La promoción no es válida.']);
        }
        if (!now()->between($promocion->fecha_inicio, $promocion->fecha_fin)) {
            return response()->json(['success' => false, 'message' => 'La promoción no está vigente.']);
        }
        if ($totalCompra < $promocion->compra_minima) {
            return response()->json(['success' => false, 'message' => 'Monto insuficiente para esta promoción.']);
        }

        session()->put('porcentaje_descuento', $promocion->descuento);
        session()->put('promocion_id', $promocion->id);

        return response()->json(['success' => true, 'message' => 'Promoción aplicada correctamente']);
    }

    // Retorna saldo de puntos real calculado desde historial
    public function getPuntosCliente(Request $request)
    {
        $cliente = Cliente::find($request->cliente_id);

        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado']);
        }

        $saldo      = $cliente->saldo_puntos; // accessor del modelo
        $descuento  = Descuento::where('estado', 1)->first();
        $puedeCanjer = $descuento && $saldo >= $descuento->puntos;

        return response()->json([
            'success'       => true,
            'puntos'        => $saldo,
            'puede_canjear' => $puedeCanjer,
            'descuento_monto' => $puedeCanjer ? $descuento->descuento : 0,
            'mensaje'       => $puedeCanjer
                ? "Puede canjear {$descuento->puntos} puntos y obtener Bs. {$descuento->descuento} de descuento"
                : "Necesita {$descuento?->puntos} puntos para canjear (tiene {$saldo})",
        ]);
    }

    public function setUsoPuntos(Request $request)
    {
        session(['usar_puntos' => $request->boolean('usar_puntos')]);
        return response()->json(['success' => true]);
    }

    public function destroy(Venta $venta){
        $venta = Venta::findOrFail($venta->id);
        $venta->estado = 0;
        $venta->save();
        session()->flash('ventas', "Se dio de baja la venta #$venta->id.");
        return redirect()->route('ventas.index');
    }
}