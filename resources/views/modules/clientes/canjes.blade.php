<x-layouts.app>
    <div class="grid gap-3 md:grid-cols-2">
        <x-module-card class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="truncate text-base font-semibold text-zinc-900 dark:text-white">{{ $cliente->nombre }}</p>
                <p class="module-card-sublabel">CI: {{ $cliente->ci }}</p>
            </div>
            <div class="shrink-0 text-right">
                <p class="text-lg font-semibold leading-none text-orange-600 dark:text-orange-500">{{ $cliente->saldo_puntos }}</p>
                <p class="module-card-sublabel mt-1">Puntos</p>
            </div>
        </x-module-card>
        <x-module-card class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h1 class="text-base font-semibold text-zinc-900 dark:text-white">Historial de movimientos</h1>
                <p class="module-card-sublabel">Puntos ganados y canjeados</p>
            </div>
            <flux:button variant="outline" size="sm" :href="route('clientes.index')" class="shrink-0">
                Volver
            </flux:button>
        </x-module-card>
    </div>

    @include('modules.clientes.partials.movimientos-table', ['movimientos' => $movimientos])
</x-layouts.app>
