<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\TipoPago;
use App\Models\User;
use App\Models\Producto;
use PDF;

class ReporteController extends Controller
{
    public function index()
    {
        return view('modules.reportes.index');
    }
    public function ventas(Request $request)
    {
        $query = Venta::with(['cliente', 'tipoPago', 'promocion']);

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('created_at', [$request->desde, $request->hasta]);
        }

        if ($request->filled('tipo_pago_id')) {
            $query->where('tipo_pago_id', $request->tipo_pago_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $ventas = $query->orderBy('created_at', 'desc')->get();

        $total = $ventas->sum('total');
        $descuentos = $ventas->sum('descuento');

        $tipoPagos = TipoPago::where('estado', 1)->orderBy('nombre')->get();

        return view('modules.reportes.ventas', compact('ventas','total','descuentos','tipoPagos'));
    }

    public function ventasPDF(Request $request)
    {
        $query = Venta::with(['cliente', 'tipoPago', 'promocion']);

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('created_at', [$request->desde, $request->hasta]);
        }

        if ($request->filled('tipo_pago_id')) {
            $query->where('tipo_pago_id', $request->tipo_pago_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $ventas = $query->orderBy('created_at', 'desc')->get();

        $total = $ventas->sum('total');
        $descuentos = $ventas->sum('descuento');

        $data = [
            'ventas' => $ventas,
            'total' => $total,
            'descuentos' => $descuentos,
            'fecha' => now()->format('d/m/Y'),
        ];

        $pdf = PDF::loadView('modules.reportes.pdf.ventas', $data);

        return $pdf->download('reporte-ventas.pdf');
    }

    public function usuarios(Request $request)
    {
        $query = User::query();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        $total = $usuarios->count();
        $activos = $usuarios->where('estado', 1)->count();
        $inactivos = $usuarios->where('estado', 0)->count();

        return view('modules.reportes.usuarios', compact(
            'usuarios',
            'total',
            'activos',
            'inactivos'
        ));
    }

    public function usuariosPDF(Request $request)
    {
        $query = User::query();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        $total = $usuarios->count();
        $activos = $usuarios->where('estado', 1)->count();
        $inactivos = $usuarios->where('estado', 0)->count();

        $data = [
            'usuarios' => $usuarios,
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
            'fecha' => now()->format('d/m/Y'),
        ];

        $pdf = PDF::loadView('modules.reportes.pdf.usuarios', $data);

        return $pdf->download('reporte-usuarios.pdf');
    }
    public function productos(Request $request)
{
    $query = Producto::with('categoria');

    // Filtros
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }

    if ($request->filled('nombre')) {
        $query->where('nombre', 'like', '%' . $request->nombre . '%');
    }

    if ($request->filled('precio_min')) {
        $query->where('precio', '>=', $request->precio_min);
    }

    if ($request->filled('precio_max')) {
        $query->where('precio', '<=', $request->precio_max);
    }

    $productos = $query->orderBy('created_at', 'desc')->get();

    // 🔹 Estadísticas
    $total = $productos->count();
    $activos = $productos->where('estado', 1)->count();
    $inactivos = $productos->where('estado', 0)->count();

    return view('modules.reportes.productos', compact(
        'productos',
        'total',
        'activos',
        'inactivos'
    ));
}

public function productosPDF(Request $request)
{
    $query = Producto::with('categoria');

    // mismos filtros
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }

    if ($request->filled('nombre')) {
        $query->where('nombre', 'like', '%' . $request->nombre . '%');
    }

    if ($request->filled('precio_min')) {
        $query->where('precio', '>=', $request->precio_min);
    }

    if ($request->filled('precio_max')) {
        $query->where('precio', '<=', $request->precio_max);
    }

    $productos = $query->orderBy('created_at', 'desc')->get();

    $total = $productos->count();
    $activos = $productos->where('estado', 1)->count();
    $inactivos = $productos->where('estado', 0)->count();

    // 🔥 extra útil
    $totalPrecio = $productos->sum('precio');

    $data = [
        'productos' => $productos,
        'total' => $total,
        'activos' => $activos,
        'inactivos' => $inactivos,
        'totalPrecio' => $totalPrecio,
        'fecha' => now()->format('d/m/Y'),
    ];

    $pdf = PDF::loadView('modules.reportes.pdf.productos', $data);

    return $pdf->download('reporte-productos.pdf');
}


}
