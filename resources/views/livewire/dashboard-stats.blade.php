<div 
    x-data="dashboardCharts"
    x-init="initCharts()"
    x-on:refresh-charts.window="updateAllCharts()"
    class="space-y-6"
>

    <!-- ========================= -->
    <!-- TARJETAS DE TOTALES -->
    <!-- ========================= -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-5 shadow rounded">
            <div class="text-gray-500 text-sm">Total Ventas</div>
            <div class="text-3xl font-bold">{{ $totalVentas }}</div>
        </div>

        <div class="bg-white p-5 shadow rounded">
            <div class="text-gray-500 text-sm">Total Ingresos</div>
            <div class="text-3xl font-bold">Bs {{ number_format($totalIngresos, 2) }}</div>
        </div>

        <div class="bg-white p-5 shadow rounded">
            <div class="text-gray-500 text-sm">Clientes Registrados</div>
            <div class="text-3xl font-bold">{{ $totalClientes }}</div>
        </div>

        <div class="bg-white p-5 shadow rounded">
            <div class="text-gray-500 text-sm">Productos</div>
            <div class="text-3xl font-bold">{{ $totalProductos }}</div>
        </div>

    </div>

    <!-- ========================= -->
    <!-- GRÁFICOS -->
    <!-- ========================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-5 shadow rounded">
            <h3 class="font-bold mb-2">Ventas últimas 7 semanas</h3>
            <div id="chartSemanal"></div>
        </div>

        <div class="bg-white p-5 shadow rounded">
            <h3 class="font-bold mb-2">Top 5 productos más vendidos</h3>
            <div id="chartProductos"></div>
        </div>

        <div class="bg-white p-5 shadow rounded">
            <h3 class="font-bold mb-2">Ingresos últimos 6 meses</h3>
            <div id="chartIngresos"></div>
        </div>

        <div class="bg-white p-5 shadow rounded">
            <h3 class="font-bold mb-2">Nuevos clientes por mes</h3>
            <div id="chartClientes"></div>
        </div>

    </div>

</div>


<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardCharts', () => ({
        charts: {},

        initCharts() {
            this.drawSemanal();
            this.drawProductos();
            this.drawIngresos();
            this.drawClientes();
        },

        destroy(chartId) {
            if (this.charts[chartId]) {
                this.charts[chartId].destroy();
            }
        },

        updateAllCharts() {
            this.initCharts();
        },

        drawSemanal() {
            this.destroy('semanal');

            this.charts.semanal = new ApexCharts(
                document.querySelector("#chartSemanal"),
                {
                    chart: { type: 'line', height: 300 },
                    stroke: { curve: 'smooth' },
                    series: [{ name: "Ventas", data: @js($ventasSemanales) }],
                    xaxis: { categories: @js($semanas) }
                }
            ).render();
        },

        drawProductos() {
            this.destroy('productos');

            this.charts.productos = new ApexCharts(
                document.querySelector("#chartProductos"),
                {
                    chart: { type: 'bar', height: 300 },
                    plotOptions: { bar: { horizontal: true } },
                    series: [{ name: "Cantidad", data: @js($topCantidades) }],
                    xaxis: { categories: @js($topProductos) }
                }
            ).render();
        },

        drawIngresos() {
            this.destroy('ingresos');

            this.charts.ingresos = new ApexCharts(
                document.querySelector("#chartIngresos"),
                {
                    chart: { type: 'area', height: 300 },
                    stroke: { curve: 'smooth' },
                    series: [{ name: "Ingresos", data: @js($ingresosMensuales) }],
                    xaxis: { categories: @js($meses) }
                }
            ).render();
        },

        drawClientes() {
            this.destroy('clientes');

            this.charts.clientes = new ApexCharts(
                document.querySelector("#chartClientes"),
                {
                    chart: { type: 'donut', height: 300 },
                    series: @js($clientesCantidad),
                    labels: @js($clientesMes)
                }
            ).render();
        }
    }))
})
</script>
