<div
    x-data="dashboardCharts(@js($this->chartPayload()))"
    x-init="$nextTick(() => initCharts())"
    x-on:refresh-charts.window="updateAllCharts($event.detail)"
    class="space-y-6"
>
    <div>
        <h1 class="module-page-title">Resumen</h1>
        <p class="module-card-sublabel mt-1">Ventas completadas, ingresos y clientes de la pizzería.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-module-card>
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="module-card-label">Total ventas</p>
                    <p class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums dark:text-white">{{ number_format($totalVentas) }}</p>
                    <p class="module-card-sublabel mt-2">Ventas completadas</p>
                </div>
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-800 dark:bg-orange-500/15 dark:text-orange-300">
                    <flux:icon name="shopping-cart" class="size-5" />
                </span>
            </div>
        </x-module-card>

        <x-module-card>
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="module-card-label">Ingresos totales</p>
                    <p class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums dark:text-white">Bs {{ number_format($totalIngresos, 2) }}</p>
                    @if ($variacionIngresos === null)
                        <p class="module-card-sublabel mt-2">Generados en ventas</p>
                    @else
                        <p @class([
                            'mt-2 text-xs font-medium',
                            'text-zinc-500 dark:text-zinc-400' => $variacionIngresos == 0,
                            'text-green-700 dark:text-green-300' => $variacionIngresos > 0,
                            'text-red-700 dark:text-red-300' => $variacionIngresos < 0,
                        ])>
                            {{ $variacionIngresos > 0 ? '+' : '' }}{{ number_format($variacionIngresos, 1) }}% vs mes anterior
                        </p>
                    @endif
                </div>
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-800 dark:bg-orange-500/15 dark:text-orange-300">
                    <flux:icon name="banknotes" class="size-5" />
                </span>
            </div>
        </x-module-card>

        <x-module-card>
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="module-card-label">Clientes</p>
                    <p class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums dark:text-white">{{ number_format($totalClientes) }}</p>
                    <p class="module-card-sublabel mt-2">{{ number_format($clientesNuevosMes) }} nuevos este mes</p>
                </div>
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-800 dark:bg-orange-500/15 dark:text-orange-300">
                    <flux:icon name="users" class="size-5" />
                </span>
            </div>
        </x-module-card>

        <x-module-card>
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="module-card-label">Productos</p>
                    <p class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums dark:text-white">{{ number_format($totalProductos) }}</p>
                    <p class="module-card-sublabel mt-2">Disponibles en el menú</p>
                </div>
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-800 dark:bg-orange-500/15 dark:text-orange-300">
                    <flux:icon name="shopping-bag" class="size-5" />
                </span>
            </div>
        </x-module-card>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <x-module-card-lg>
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h2 class="module-card-title text-base">Ingresos últimas 7 semanas</h2>
                    <p class="module-card-sublabel mt-1">Monto de ventas completadas por semana</p>
                </div>
                @if ($variacionSemanal !== null)
                    <span @class([
                        'inline-flex shrink-0 rounded-full px-2.5 py-1 text-xs font-medium',
                        'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' => $variacionSemanal == 0,
                        'bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-300' => $variacionSemanal > 0,
                        'bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-300' => $variacionSemanal < 0,
                    ])>
                        {{ $variacionSemanal > 0 ? '+' : '' }}{{ number_format($variacionSemanal, 1) }}%
                    </span>
                @endif
            </div>
            <div id="chartSemanal" class="min-h-[280px]"></div>
        </x-module-card-lg>

        <x-module-card-lg>
            <div class="mb-4">
                <h2 class="module-card-title text-base">Top 5 productos</h2>
                <p class="module-card-sublabel mt-1">Unidades vendidas en ventas completadas</p>
            </div>
            <div id="chartProductos" class="min-h-[280px]"></div>
        </x-module-card-lg>

        <x-module-card-lg>
            <div class="mb-4">
                <h2 class="module-card-title text-base">Ingresos últimos 6 meses</h2>
                <p class="module-card-sublabel mt-1">Evolución del monto cobrado</p>
            </div>
            <div id="chartIngresos" class="min-h-[280px]"></div>
        </x-module-card-lg>

        <x-module-card-lg>
            <div class="mb-4">
                <h2 class="module-card-title text-base">Nuevos clientes por mes</h2>
                <p class="module-card-sublabel mt-1">Altas registradas en los últimos 6 meses</p>
            </div>
            <div id="chartClientes" class="min-h-[280px]"></div>
        </x-module-card-lg>
    </div>
</div>
