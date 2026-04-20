<x-layouts.app>
    <x-card-header
        title="Historial de Canjes"
        description="Consulta los canjes del cliente seleccionado"
        button-text="Volver a clientes"
        :button-link="route('clientes.index')"
    />

    <div class="mt-4 mb-6 rounded-xl border border-gray-200 bg-white p-4">
        <p class="text-sm text-gray-500">Cliente</p>
        <p class="text-lg font-semibold text-gray-900">
            {{ $cliente->nombre }}
        </p>
        <p class="text-sm text-gray-600">
            CI: {{ $cliente->ci }}
        </p>
    </div>

    @include('modules.clientes.partials.canjes-table', ['canjes' => $canjes])
</x-layouts.app>
