<x-layouts.app>
    <x-card-header
        title="Historial de Canjes"
        description="Consulta los canjes del cliente seleccionado"
        button-text="Volver a clientes"
        :button-link="route('clientes.index')"
    />

    <x-module-card class="mt-4 mb-6">
        <p class="module-card-label">Cliente</p>
        <p class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ $cliente->nombre }}
        </p>
        <p class="module-card-sublabel mt-1">
            CI: {{ $cliente->ci }}
        </p>
    </x-module-card>

    @include('modules.clientes.partials.canjes-table', ['canjes' => $canjes])
</x-layouts.app>
