<x-layouts.app>
    <div class="p-6">

        @php
            $reportes = [
                [
                    'titulo'      => 'Reporte de Ventas',
                    'descripcion' => 'Filtra por fechas, tipo de pago y estado.',
                    'url'         => route('reportes.ventas'),
                    'icono'       => 'chart-bar',
                ],
                [
                    'titulo'      => 'Reporte de Usuarios',
                    'descripcion' => 'Filtra por estado.',
                    'url'         => route('reportes.usuarios'),
                    'icono'       => 'users',
                ],
                [
                    'titulo'      => 'Reporte de Productos',
                    'descripcion' => 'Filtra por fecha, precio y estado.',
                    'url'         => route('reportes.productos'),
                    'icono'       => 'archive-box',
                ],
            ];
        @endphp

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

            @foreach ($reportes as $reporte)
                <x-module-card>
                    <a href="{{ $reporte['url'] }}" class="group flex flex-col justify-between h-full">

                        <!-- Icono + Título / Descripción -->
                        <div class="flex items-start gap-4">
                            <!-- Icono -->
                            <div class="shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-900">
                                <flux:icon :name="$reporte['icono']" class="w-6 h-6 text-orange-500 dark:text-orange-300" />
                            </div>

                            <!-- Título y descripción -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-orange-500">
                                    {{ $reporte['titulo'] }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $reporte['descripcion'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer siempre abajo -->
                        <div class="mt-4 flex items-center justify-end">
                            <span class="text-sm text-orange-500 dark:text-orange-400 font-medium">Ver reporte</span>
                            <!-- <span class="text-orange-500 dark:text-orange-400 font-medium group-hover:translate-x-1 transition">→</span> -->
                        </div>

                    </a>
                </x-module-card>
            @endforeach

        </div>

    </div>
</x-layouts.app>