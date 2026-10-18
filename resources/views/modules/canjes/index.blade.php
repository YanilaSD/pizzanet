<x-layouts.app>
    <x-card-header 
        title="Gestión de Canjes"
        description="Administra los canjes por puntos del sistema"
        button-text="Nuevo canje"
        :button-link="route('canjes.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl my-4">
            <strong>¡Éxito!</strong>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Canjes Activos"
        description="Solo debe existir un canje activo a la vez"
    >
        <x-slot name="actions">
            <x-module-search-form
                :action="route('canjes.index')"
                placeholder="Buscar canje"
                :value="request('search')"
            />
        </x-slot>

        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Puntos</th>
            <th>Monto (Bs)</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($canjes as $canje)
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->iteration }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $canje->nombre }}
                    </td>

                    <td class="module-table-td">
                        {{ $canje->descripcion }}
                    </td>

                    <td class="module-table-td">
                        {{ $canje->puntos }}
                    </td>

                    <td class="module-table-td">
                        Bs {{ number_format($canje->descuento, 2) }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $canje->estado == 1 ? 'green' : 'red' }}">
                            {{ $canje->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="module-table-td-actions">
                        <a href="{{ route('canjes.edit', $canje) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('canjes.toggle', $canje->id) }}"
                           data-confirm="¿Desea {{ $canje->estado == 1 ? 'desactivar' : 'activar' }} este canje?"
                           data-confirm-title="{{ $canje->estado == 1 ? 'Desactivar canje' : 'Activar canje' }}"
                           data-confirm-variant="question"
                           data-confirm-confirm="Sí, {{ $canje->estado == 1 ? 'desactivar' : 'activar' }}"
                           class="{{ $canje->estado == 1 ? 'module-icon-btn-red' : 'module-icon-btn-green' }}">
                            <flux:icon name="{{ $canje->estado == 1 ? 'no-symbol' : 'check-circle' }}" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="module-table-empty">
                        No se encontraron canjes activos.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $canjes->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>
