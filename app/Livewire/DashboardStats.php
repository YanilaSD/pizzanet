<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    public $totalVentas;
    public $totalIngresos;
    public $totalClientes;
    public $totalProductos;

    public $semanas = [];
    public $ventasSemanales = [];

    public $topProductos = [];
    public $topCantidades = [];

    public $meses = [];
    public $ingresosMensuales = [];

    public $clientesMes = [];
    public $clientesCantidad = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // 1. Totales — solo ventas completadas
        $this->totalVentas    = Venta::where('estado', 1)->count();
        $this->totalIngresos  = Venta::where('estado', 1)->sum('total');
        $this->totalClientes  = Cliente::where('estado', 1)->count();
        $this->totalProductos = Producto::where('estado', 1)->count();

        // 2. Ventas semanales (últimas 7 semanas)
        $this->semanas = [];
        $this->ventasSemanales = [];
        for ($i = 6; $i >= 0; $i--) {
            $inicio = Carbon::now()->subWeeks($i)->startOfWeek();
            $fin    = Carbon::now()->subWeeks($i)->endOfWeek();
            $this->semanas[]         = $inicio->format('d M');
            $this->ventasSemanales[] = Venta::where('estado', 1)
                ->whereBetween('created_at', [$inicio, $fin])
                ->sum('total');
        }

        // 3. Top 5 productos más vendidos
        $productos = Producto::select('productos.nombre', DB::raw('SUM(detalle_ventas.cantidad) as vendidos'))
            ->join('detalle_ventas', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.estado', 1)
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('vendidos')
            ->take(5)
            ->get();

        $this->topProductos  = $productos->pluck('nombre')->values();
        $this->topCantidades = $productos->pluck('vendidos')->values();

        // 4. Ingresos últimos 6 meses
        $this->meses = [];
        $this->ingresosMensuales = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $this->meses[]             = $mes->format('M');
            $this->ingresosMensuales[] = Venta::where('estado', 1)
                ->whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->sum('total');
        }

        // 5. Clientes últimos 6 meses
        $this->clientesMes = [];
        $this->clientesCantidad = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $this->clientesMes[]      = $mes->format('M');
            $this->clientesCantidad[] = Cliente::whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->count();
        }
    }

    public function refreshCharts()
    {
        $this->loadStats();
        $this->dispatch('refreshCharts');
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}