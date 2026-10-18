<x-layouts.app>
    <x-card-header 
        title="Gestión de Festividades"
        description="Registra y gestiona todas las festividades del sistema."
        button-text="Nueva festividad"
        :button-link="route('festividades.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Registro de Festividades"
        description="Listado de festividades registradas"
    >
        <x-slot name="actions">
            <x-module-search-form
                :action="route('festividades.index')"
                placeholder="Buscar festividad"
                :value="request('search')"
            />
        </x-slot>

        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($festividades as $festividad)
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->iteration }}
                    </td>
                    <td class="module-table-td-primary">
                        {{ $festividad->nombre }}
                    </td>
                    <td class="module-table-td">
                        {{ $festividad->descripcion }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:badge color="{{ $festividad->estado == '1' ? 'green' : 'red' }}">
                            {{ $festividad->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>
                    <td class="module-table-td-actions">
                        <a href="{{ route('festividades.edit', $festividad) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>
                        <a href="{{ route('festividades.destroy', $festividad->id) }}"
                           data-confirm=""
                           data-confirm-title="¿Eliminar la festividad {{ $festividad->nombre }}?"
                           data-confirm-variant="danger"
                           class="module-icon-btn-red">
                            <flux:icon name="trash" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="module-table-empty">
                        No se encontraron festividades registradas.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $festividades->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>
