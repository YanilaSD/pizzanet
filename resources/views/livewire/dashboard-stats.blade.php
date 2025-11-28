<div 
    x-data="dashboardCharts()"
    x-init="$nextTick(() => initCharts())"
    x-on:refresh-charts.window="updateAllCharts()"
    class="space-y-6"
>

    <!-- ========================= -->
    <!-- TARJETAS DE TOTALES -->
    <!-- ========================= -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Total Ventas -->
        <div class="relative p-5 rounded-xl shadow-xl text-white bg-gradient-to-br from-orange-500 to-orange-700 overflow-hidden">
            <div class="absolute right-3 top-3 opacity-20">
                <svg
                xmlns="http://www.w3.org/2000/svg"
                width="90"
                height="90"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#000000"
                stroke-width="1"
                stroke-linecap="round"
                stroke-linejoin="round"
                >
                <path d="M9 15l6 -6" />
                <circle cx="9.5" cy="9.5" r=".5" fill="currentColor" />
                <circle cx="14.5" cy="14.5" r=".5" fill="currentColor" />
                <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" />
                </svg>

            </div>

            <div class="text-sm opacity-90">Total Ventas</div>
            <div class="text-4xl font-extrabold">{{ $totalVentas }}</div>
            <div class="mt-2 text-xs opacity-70">Ventas registradas</div>
        </div>

        <!-- Total Ingresos -->
        <div class="relative p-5 rounded-xl shadow-xl text-white bg-gradient-to-br from-yellow-500 to-orange-600 overflow-hidden">
            <div class="absolute right-3 top-3 opacity-20">
                <svg
                xmlns="http://www.w3.org/2000/svg"
                width="90"
                height="90"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#000000"
                stroke-width="1"
                stroke-linecap="round"
                stroke-linejoin="round"
                >
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                <path d="M13.867 9.75c-.246 -.48 -.708 -.769 -1.2 -.75h-1.334c-.736 0 -1.333 .67 -1.333 1.5c0 .827 .597 1.499 1.333 1.499h1.334c.736 0 1.333 .671 1.333 1.5c0 .828 -.597 1.499 -1.333 1.499h-1.334c-.492 .019 -.954 -.27 -1.2 -.75" />
                <path d="M12 7v2" />
                <path d="M12 15v2" />
                </svg>

            </div>

            <div class="text-sm opacity-90">Ingresos Totales</div>
            <div class="text-4xl font-extrabold">Bs {{ number_format($totalIngresos, 2) }}</div>
            <div class="mt-2 text-xs opacity-70">Generados en ventas</div>
        </div>

        <!-- Clientes Registrados -->
        <div class="relative p-5 rounded-xl shadow-xl text-white bg-gradient-to-br from-red-500 to-orange-700 overflow-hidden">
            <div class="absolute right-3 top-3 opacity-20">
                <svg
                xmlns="http://www.w3.org/2000/svg"
                width="90"
                height="90"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#000000"
                stroke-width="1"
                stroke-linecap="round"
                stroke-linejoin="round"
                >
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h3" />
                <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                <path d="M19 21v1m0 -8v1" />
                </svg>

            </div>

            <div class="text-sm opacity-90">Clientes</div>
            <div class="text-4xl font-extrabold">{{ $totalClientes }}</div>
            <div class="mt-2 text-xs opacity-70">Registrados en el sistema</div>
        </div>

        <!-- Productos -->
        <div class="relative p-5 rounded-xl shadow-xl text-white bg-gradient-to-br from-orange-400 to-red-600 overflow-hidden">
            <div class="absolute right-3 top-3 opacity-20">
                <svg
                xmlns="http://www.w3.org/2000/svg"
                width="90"
                height="90"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#000000"
                stroke-width="1"
                stroke-linecap="round"
                stroke-linejoin="round"
                >
                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                <path d="M12 12l8 -4.5" />
                <path d="M12 12l0 9" />
                <path d="M12 12l-8 -4.5" />
                <path d="M16 5.25l-8 4.5" />
                </svg>

            </div>

            <div class="text-sm opacity-90">Productos</div>
            <div class="text-4xl font-extrabold">{{ $totalProductos }}</div>
            <div class="mt-2 text-xs opacity-70">Disponibles en menú</div>
        </div>
    </div>


    <!-- ========================= -->
    <!-- GRÁFICOS -->
    <!-- ========================= -->
   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="p-5 rounded-xl shadow-xl bg-gradient-to-br from-orange-50 to-yellow-100 border border-orange-200">
            <h3 class="font-bold mb-2">Ventas últimas 7 semanas</h3>
            <div id="chartSemanal"></div>
        </div>

        <div class="p-5 rounded-xl shadow-xl bg-gradient-to-br from-orange-50 to-yellow-100 border border-orange-200">
            <h3 class="font-bold mb-2">Top 5 productos más vendidos</h3>
            <div id="chartProductos"></div>
        </div>

        <div class="p-5 rounded-xl shadow-xl bg-gradient-to-br from-orange-50 to-yellow-100 border border-orange-200">
            <h3 class="font-bold mb-2">Ingresos últimos 6 meses</h3>
            <div id="chartIngresos"></div>
        </div>

        <div class="p-5 rounded-xl shadow-xl bg-gradient-to-br from-orange-50 to-yellow-100 border border-orange-200">
            <h3 class="font-bold mb-2">Nuevos clientes por mes</h3>
            <div id="chartClientes"></div>
        </div>

    </div>


</div>

<script>
window.chartSemanalConfig = {
    chart: { type: 'line', height: 320, toolbar: { show: false }},
    stroke: { curve: 'smooth', width: 4, colors: ['#FF6A00'] },
    series: [{ name: "Ventas", data: @js($ventasSemanales) }],
    xaxis: { categories: @js($semanas) },
    tooltip: { theme: 'dark' }
};

window.chartProductosConfig = {
    chart: { type: 'bar', height: 320 },
    plotOptions: { bar: { horizontal: true, borderRadius: 8 }},
    colors: ['#E63946'],
    series: [{ name: "Vendidos", data: @js($topCantidades) }],
    xaxis: { categories: @js($topProductos) }
};

window.chartIngresosConfig = {
    chart: { type: 'area', height: 320 },
    stroke: { curve: 'smooth', width: 4, colors: ['#FF4500'] },
    fill: { type: 'gradient', gradient: { shadeIntensity: 0.7, opacityFrom: 0.7, opacityTo: 0.3 }},
    series: [{ name: "Ingresos", data: @js($ingresosMensuales) }],
    xaxis: { categories: @js($meses) }
};

window.chartClientesConfig = {
    chart: { type: 'donut', height: 320 },
    colors: pizzaColors,
    series: @js($clientesCantidad),
    labels: @js($clientesMes),
    legend: { position: 'bottom' }
};
</script>