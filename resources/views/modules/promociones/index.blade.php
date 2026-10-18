<x-layouts.app>
    <x-card-header 
        title="Gestión de Promociones"
        description="Registra y gestiona todas las promociones del sistema."
        button-text="Nueva promoción"
        :button-link="route('promociones.create')"
    />

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg my-4" role="alert">
            <div class="font-semibold">¡Éxito!</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <x-data-table 
        title="Registro de Promociones"
        description="Listado de promociones registradas"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('promociones.index') }}">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Buscar promoción"
                    value="{{ request('search') }}"
                />
            </form>
        </x-slot>

        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Festividad</th>
            <th>Descuento</th>
            <th>Vigencia</th>
            <th>Compra mínima</th>
            <th>Límite de uso</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($promociones as $promocion)
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->iteration }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $promocion->nombre }}
                    </td>

                    <td class="module-table-td whitespace-nowrap">
                        {{ $promocion->festividad->nombre }}
                    </td>

                    <td class="module-table-td whitespace-nowrap">
                        {{ rtrim(rtrim(number_format($promocion->descuento, 2), '0'), '.') }}%
                    </td>

                    <td class="module-table-td whitespace-nowrap text-xs">
                        {{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}
                    </td>

                    <td class="module-table-td whitespace-nowrap">
                        Bs {{ number_format($promocion->compra_minima, 2) }}
                    </td>

                    <td class="module-table-td whitespace-nowrap">
                        {{ $promocion->limite_uso }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="{{ $promocion->colorEstado() }}">
                            {{ $promocion->etiquetaEstado() }}
                        </flux:badge>
                    </td>

                    <td class="module-table-td-actions">
                        <a href="{{ route('promociones.edit', $promocion->id) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('promociones.destroy', $promocion) }}"
                           data-confirm=""
                           data-confirm-title="¿Eliminar la promoción {{ $promocion->nombre }}?"
                           data-confirm-variant="danger"
                           class="module-icon-btn-red">
                            <flux:icon name="trash" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="module-table-empty">
                        No se encontraron promociones registradas.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $promociones->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>