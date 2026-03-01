<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\TipoPago;

class ReporteController extends Controller
{
    public function index()
    {
        return view('modules.reportes.index');
    }
    public function ventas(Request $request)
    {
        $query = Venta::with(['cliente', 'tipoPago', 'promocion']);

        // Fechas (en tu migración "fecha" es DATE)
        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha', [$request->desde, $request->hasta]);
        }

        if ($request->filled('tipo_pago_id')) {
            $query->where('tipo_pago_id', $request->tipo_pago_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $ventas = $query->orderBy('fecha', 'desc')->get();

        $total = $ventas->sum('total');
        $descuentos = $ventas->sum('descuento');

        $tipoPagos = TipoPago::where('estado', 1)->orderBy('nombre')->get();

        return view('modules.reportes.ventas', compact('ventas','total','descuentos','tipoPagos'));
    }
}
