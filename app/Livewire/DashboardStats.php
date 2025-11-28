<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use Carbon\Carbon;

class DashboardStats extends Component
{
    // Totales
    public $totalVentas;
    public $totalIngresos;
    public $totalClientes;
    public $totalProductos;

    // Ventas semanales
    public $semanas = [];
    public $ventasSemanales = [];

    // Top productos
    public $topProductos = [];
    public $topCantidades = [];

    // Ingresos últimos meses
    public $meses = [];
    public $ingresosMensuales = [];

    // Clientes últimos meses
    public $clientesMes = [];
    public $clientesCantidad = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // ======================
        // 1. Totales
        // ======================
        $this->totalVentas = Venta::count();
        $this->totalIngresos = Venta::sum('total');
        $this->totalClientes = Cliente::count();
        $this->totalProductos = Producto::count();

        // ======================
        // 2. Ventas semanales (últimas 7 semanas)
        // ======================
        $this->semanas = [];
        $this->ventasSemanales = [];

        for ($i = 6; $i >= 0; $i--) {
            $inicio = Carbon::now()->subWeeks($i)->startOfWeek();
            $fin = Carbon::now()->subWeeks($i)->endOfWeek();

            $this->semanas[] = $inicio->format('d M');

            $this->ventasSemanales[] = Venta::whereBetween('fecha', [$inicio, $fin])
                ->sum('total');
        }

        // ======================
        // 3. Top 5 productos más vendidos
        // ======================
        $productos = Producto::with('detalleVentas')
            ->get()
            ->map(function ($p) {
                $p->vendidos = $p->detalleVentas->sum('cantidad');
                return $p;
            })
            ->sortByDesc('vendidos')
            ->take(5);

        $this->topProductos = $productos->pluck('nombre')->values();
        $this->topCantidades = $productos->pluck('vendidos')->values();

        // ======================
        // 4. Ingresos últimos 6 meses
        // ======================
        $this->meses = [];
        $this->ingresosMensuales = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);

            $this->meses[] = $mes->format('M');

            $this->ingresosMensuales[] = Venta::whereYear('fecha', $mes->year)
                ->whereMonth('fecha', $mes->month)
                ->sum('total');
        }

        // ======================
        // 5. Clientes últimos 6 meses
        // ======================
        $this->clientesMes = [];
        $this->clientesCantidad = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);

            $this->clientesMes[] = $mes->format('M');

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
