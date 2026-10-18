<x-layouts.app>

    <x-card-header 
        title="Gestión de Privilegios"
        description="Administra y controla los privilegios del sistema"
        button-text="Nuevo privilegio"
        :button-link="route('privilegios.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl my-4">
            <strong>¡Éxito!</strong>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Registro de Privilegios"
        description="Listado de los privilegios registrados"
    >

        <x-slot name="actions">
            <form method="GET" action="{{ route('privilegios.index') }}">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Buscar privilegio"
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
            @forelse ($privilegios as $privilegio)
                <tr class="module-table-tr">

                    <td class="module-table-td-index">
                        {{ $loop->iteration }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $privilegio->nombre }}
                    </td>

                    <td class="module-table-td">
                        {{ $privilegio->descripcion }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $privilegio->estado == '1' ? 'green' : 'red' }}">
                            {{ $privilegio->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="module-table-td-actions">

                        <a href="{{ route('privilegios.edit', $privilegio) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('privilegios.toggle', $privilegio->id) }}"
                           data-confirm="¿Desea {{ $privilegio->estado == '1' ? 'desactivar' : 'activar' }} el privilegio {{ $privilegio->nombre }}?"
                           data-confirm-title="{{ $privilegio->estado == '1' ? 'Desactivar privilegio' : 'Activar privilegio' }}"
                           data-confirm-variant="question"
                           data-confirm-confirm="Sí, {{ $privilegio->estado == '1' ? 'desactivar' : 'activar' }}"
                           class="{{ $privilegio->estado == '1' ? 'module-icon-btn-red' : 'module-icon-btn-green' }}">
                            <flux:icon name="{{ $privilegio->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="module-table-empty">
                        No se encontraron registros
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $privilegios->links() }}
        </x-slot>

    </x-data-table>

</x-layouts.app>
