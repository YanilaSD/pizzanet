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
        return view('dashboard');
    }

    public function predashboard()
    {
        return view('pre-dashboard');
    }

    public function client(Request $request)
    {
        // ================================
        // VALIDACIÓN
        // ================================
        $request->validate([
            'ci' => 'required|string|max:20'
        ], [
            'ci.required' => 'El número de CI es obligatorio.',
            'ci.string' => 'El CI debe ser un texto válido.',
            'ci.max' => 'El CI no puede exceder 20 caracteres.',
        ]);

        // ================================
        // BUSCAR CLIENTE POR CI
        // ================================
        $cliente = Cliente::where('ci', $request->ci)->first();

        if (!$cliente) {
            return back()->withErrors(['ci' => 'No existe un cliente registrado con ese número de CI.']);
        }

        // ================================
        // ESTADÍSTICAS DEL CLIENTE
        // ================================
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $totalVentas = $cliente->ventas()
            ->whereYear('created_at', $anioActual)
            ->whereMonth('created_at', $mesActual)
            ->sum('total');

        $comprasRealizadas = $cliente->ventas()
            ->whereYear('created_at', $anioActual)
            ->whereMonth('created_at', $mesActual)
            ->count();

        // ================================
        // LISTAS DE PRODUCTOS Y PROMOS
        // ================================
        $promociones = Promocion::where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $productos = Producto::where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // ================================
        // RESPUESTA
        // ================================
        return view('modules.public.verificar', compact(
            'productos',
            'promociones',
            'cliente',
            'totalVentas',
            'comprasRealizadas'
        ));
    }

}
