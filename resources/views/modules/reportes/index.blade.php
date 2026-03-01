<x-layouts.app>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestión de Reportes</h1>
                <p class="text-gray-600">Selecciona el reporte que deseas generar.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('reportes.ventas') }}"
               class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                <h2 class="text-lg font-semibold text-gray-800">Reporte de Ventas</h2>
                <p class="text-gray-600 text-sm mt-2">
                    Filtra por fechas, tipo de pago y estado.
                </p>
            </a>
        </div>
    </div>
</x-layouts.app>
