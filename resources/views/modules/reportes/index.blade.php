<x-layouts.app>
    <div class="p-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Gestión de Reportes
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Selecciona el reporte que deseas generar.
                </p>
            </div>
        </div>

        <!-- Grid responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Card -->
            <a href="{{ route('reportes.ventas') }}" class="group block">
                <div class="h-full bg-white dark:bg-zinc-800 rounded-2xl shadow-md p-6 border border-gray-200 dark:border-zinc-700 
                            hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <!-- Icon -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="w-6 h-6 text-blue-600 dark:text-blue-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 10h11M9 21V3m12 18V3m0 18H9" />
                        </svg>
                    </div>

                    <!-- Title -->
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600">
                        Reporte de Ventas
                    </h2>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Filtra por fechas, tipo de pago y estado.
                    </p>

                    <!-- Footer -->
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Ver reporte
                        </span>

                        <span class="text-blue-600 dark:text-blue-400 font-medium group-hover:translate-x-1 transition">
                            →
                        </span>
                    </div>
                    
                </div>
            </a>

            <a href="{{ route('reportes.usuarios') }}" class="group block">
                <div class="h-full bg-white dark:bg-zinc-800 rounded-2xl shadow-md p-6 border border-gray-200 dark:border-zinc-700 
                            hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <!-- Icon -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="w-6 h-6 text-blue-600 dark:text-blue-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 10h11M9 21V3m12 18V3m0 18H9" />
                        </svg>
                    </div>

                    <!-- Title -->
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600">
                        Reporte de Usuarios
                    </h2>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Filtra por estado.
                    </p>

                    <!-- Footer -->
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Ver reporte
                        </span>

                        <span class="text-blue-600 dark:text-blue-400 font-medium group-hover:translate-x-1 transition">
                            →
                        </span>
                    </div>
                    
                </div>
            </a>
            <!-- Puedes duplicar este bloque para más reportes -->

        </div>

    </div>
</x-layouts.app>
