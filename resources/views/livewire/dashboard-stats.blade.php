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

        <x-module-card>
            <p class="module-card-label">Total Ventas</p>
            <p class="mt-1 text-2xl font-extrabold text-orange-600">{{ $totalVentas }}</p>
            <p class="module-card-sublabel mt-1">Ventas registradas</p>
        </x-module-card>

        <x-module-card>
            <p class="module-card-label">Ingresos Totales</p>
            <p class="mt-1 text-2xl font-extrabold text-yellow-600 dark:text-yellow-500">Bs {{ number_format($totalIngresos, 2) }}</p>
            <p class="module-card-sublabel mt-1">Generados en ventas</p>
        </x-module-card>

        <x-module-card>
            <p class="module-card-label">Clientes</p>
            <p class="mt-1 text-2xl font-extrabold text-red-600">{{ $totalClientes }}</p>
            <p class="module-card-sublabel mt-1">Registrados en el sistema</p>
        </x-module-card>

        <x-module-card>
            <p class="module-card-label">Productos</p>
            <p class="mt-1 text-2xl font-extrabold text-blue-600">{{ $totalProductos }}</p>
            <p class="module-card-sublabel mt-1">Disponibles en menú</p>
        </x-module-card>
    </div>


    <!-- ========================= -->
    <!-- GRÁFICOS -->
    <!-- ========================= -->
   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <x-module-card-lg>
            <h3 class="module-card-label mb-4 font-semibold text-gray-900 dark:text-white">Ventas últimas 7 semanas</h3>
            <div id="chartSemanal"></div>
        </x-module-card-lg>

        <x-module-card-lg>
            <h3 class="module-card-label mb-4 font-semibold text-gray-900 dark:text-white">Top 5 productos más vendidos</h3>
            <div id="chartProductos"></div>
        </x-module-card-lg>

        <x-module-card-lg>
            <h3 class="module-card-label mb-4 font-semibold text-gray-900 dark:text-white">Ingresos últimos 6 meses</h3>
            <div id="chartIngresos"></div>
        </x-module-card-lg>

        <x-module-card-lg>
            <h3 class="module-card-label mb-4 font-semibold text-gray-900 dark:text-white">Nuevos clientes por mes</h3>
            <div id="chartClientes"></div>
        </x-module-card-lg>

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
