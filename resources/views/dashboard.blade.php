<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto p-4">

        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

        <!-- Cards -->
       <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            <!-- Ventas Totales -->
            <div class="flex justify-between items-center bg-[#E44B27]/80 text-white p-6 rounded-2xl shadow-md">
                <div>
                    <h2 class="text-lg font-semibold">Ventas Totales</h2>
                    <p class="text-3xl font-bold mt-1">{{ $totalVentas }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h4l3 10h4l3-10h4" />
                    </svg>
                </div>
            </div>

            <!-- Ingresos Totales -->
            <div class="flex justify-between items-center bg-[#F97316]/80 text-white p-6 rounded-2xl shadow-md">
                <div>
                    <h2 class="text-lg font-semibold">Ingresos Totales</h2>
                    <p class="text-3xl font-bold mt-1">Bs {{ number_format($totalIngresos,2) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-3 0-5 2-5 5s2 5 5 5 5-2 5-5-2-5-5-5z" />
                    </svg>
                </div>
            </div>

            <!-- Clientes -->
            <div class="flex justify-between items-center bg-[#FB923C]/80 text-white p-6 rounded-2xl shadow-md">
                <div>
                    <h2 class="text-lg font-semibold">Clientes</h2>
                    <p class="text-3xl font-bold mt-1">{{ $totalClientes }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <!-- Productos -->
            <div class="flex justify-between items-center bg-[#EA580C]/80 text-white p-6 rounded-2xl shadow-md">
                <div>
                    <h2 class="text-lg font-semibold">Productos</h2>
                    <p class="text-3xl font-bold mt-1">{{ $totalProductos }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                </div>
            </div>
        </div>



        <!-- Gráficos -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Ventas Semanales -->
            <div class="bg-white p-4 shadow rounded">
                <h2 class="text-lg font-semibold mb-2">Ventas Semanales</h2>
                <canvas id="ventasSemanalesChart" height="200"></canvas>
            </div>

            <!-- Productos Más Vendidos -->
            <div class="bg-white p-4 shadow rounded">
                <h2 class="text-lg font-semibold mb-2">Productos Más Vendidos</h2>
                <canvas id="productosChart" height="200"></canvas>
            </div>
        </div>
    </div>
</x-layouts.app>
