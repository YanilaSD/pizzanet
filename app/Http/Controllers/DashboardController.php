<?php

namespace App\Http\Controllers;

use App\Models\CanjePunto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Promocion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function portal()
    {
        $cliente = $this->clientePortal();

        if (! $cliente) {
            return view('modules.public.portal');
        }

        return view('modules.public.verificar', $this->datosPortal($cliente));
    }

    public function client(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:20',
        ], [
            'ci.required' => 'El número de CI es obligatorio.',
            'ci.string' => 'El CI debe ser un texto válido.',
            'ci.max' => 'El CI no puede exceder 20 caracteres.',
        ]);

        $cliente = Cliente::where('ci', $request->ci)->first();

        if (! $cliente) {
            return back()
                ->withErrors(['ci' => 'No existe un cliente registrado con ese número de CI.'])
                ->withInput();
        }

        session(['portal_cliente_id' => $cliente->id]);

        return redirect()->route('cliente.portal');
    }

    public function salirPortal()
    {
        session()->forget('portal_cliente_id');

        return redirect()->route('cliente.portal');
    }

    private function clientePortal(): ?Cliente
    {
        $clienteId = session('portal_cliente_id');

        if (! $clienteId) {
            return null;
        }

        $cliente = Cliente::find($clienteId);

        if (! $cliente) {
            session()->forget('portal_cliente_id');

            return null;
        }

        return $cliente;
    }

    private function datosPortal(Cliente $cliente): array
    {
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

        $promociones = Promocion::with('festividad')
            ->where('estado', Promocion::ESTADO_ACTIVO)
            ->orderByDesc('created_at')
            ->get();

        $productos = Producto::with(['categoria', 'inventario'])
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $categorias = $productos
            ->pluck('categoria')
            ->filter()
            ->unique('id')
            ->values();

        $canje = CanjePunto::where('estado', 1)->first();
        $movimientos = $cliente->movimientosPuntos(10, 'movimientos_page');

        return compact(
            'productos',
            'categorias',
            'promociones',
            'canje',
            'cliente',
            'totalVentas',
            'comprasRealizadas',
            'movimientos'
        );
    }
}
