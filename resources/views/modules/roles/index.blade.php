<x-layouts.app>

    <x-card-header 
        title="Gestión de Roles"
        description="Registra y gestiona todos los roles del sistema"
        button-text="Nuevo rol"
        :button-link="route('roles.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Registro de Roles"
        description="Listado de los roles registrados"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('roles.index') }}">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Buscar rol"
                    value="{{ request('search') }}"
                />
            </form>
        </x-slot>

        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($roles as $rol)
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->iteration }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $rol->nombre }}
                    </td>

                    <td class="module-table-td">
                        {{ $rol->descripcion }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $rol->estado == '1' ? 'green' : 'red' }}">
                            {{ $rol->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="module-table-td-actions">
                        <a href="{{ route('roles.edit', $rol->id) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('roles.toggle', $rol->id) }}"
                           data-confirm="¿Desea {{ $rol->estado == '1' ? 'desactivar' : 'activar' }} el rol {{ $rol->nombre }}?"
                           data-confirm-title="{{ $rol->estado == '1' ? 'Desactivar rol' : 'Activar rol' }}"
                           data-confirm-variant="question"
                           data-confirm-confirm="Sí, {{ $rol->estado == '1' ? 'desactivar' : 'activar' }}"
                           class="module-icon-btn-red">
                            <flux:icon name="{{ $rol->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="module-table-empty">
                        No se encontraron roles registrados.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $roles->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>