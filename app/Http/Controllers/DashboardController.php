<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Promocion;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Totales
    public function index()
    {
        $totalVentas = Venta::count();
        $totalIngresos = Venta::sum('total');
        $totalClientes = Cliente::count();
        $totalProductos = Producto::count();

        // Ventas semanales (últimas 7 semanas)
        $semanas = collect();
        $ventasSemanales = collect();

        for ($i = 6; $i >= 0; $i--) {
            $fechaInicio = Carbon::now()->subWeeks($i)->startOfWeek();
            $fechaFin = Carbon::now()->subWeeks($i)->endOfWeek();

            $semanas->push($fechaInicio->format('d M'));
            $ventasSemanales->push(
                Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total')
            );
        }

        // Productos más vendidos
        $productos = Producto::with('detalleVentas')
            ->get()
            ->map(function ($producto) {
                $producto->vendidos = $producto->detalleVentas->sum('cantidad');
                return $producto;
            })
            ->sortByDesc('vendidos')
            ->take(5); // top 5

        $nombresProductos = $productos->pluck('nombre');
        $cantidadVendida = $productos->pluck('vendidos');

        return view('dashboard', compact(
            'totalVentas', 'totalIngresos', 'totalClientes', 'totalProductos',
            'semanas', 'ventasSemanales', 'nombresProductos', 'cantidadVendida'
        ));
    }

    public function predashboard()
    {
        return view('pre-dashboard');
    }

    public function client()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        $totalVentas = Cliente::find(1)
        ->ventas()
        ->whereYear('created_at', $anioActual)
        ->whereMonth('created_at', $mesActual)
        ->sum('total'); 
        $comprasRealizadas = Cliente::find(1)
        ->ventas()
        ->whereYear('created_at', $anioActual)
        ->whereMonth('created_at', $mesActual)
        ->count();
        $promociones = Promocion::where('estado', 1)->orderBy('created_at', 'desc')->paginate(10);
        $productos = Producto::where('estado', 1)->orderBy('created_at', 'desc')->paginate(10);

        $cliente = Cliente::findOrFail(1);
        return view('modules.public.verificar', compact('productos', 'promociones', 'cliente', 'totalVentas', 'comprasRealizadas'));
    }
}
