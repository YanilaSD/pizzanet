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
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">

                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $privilegio->nombre }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        {{ $privilegio->descripcion }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $privilegio->estado == '1' ? 'green' : 'red' }}">
                            {{ $privilegio->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 flex justify-end gap-2">

                        <a href="{{ route('privilegios.edit', $privilegio) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('privilegios.toggle', $privilegio->id) }}"
                           class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                            <flux:icon name="{{ $privilegio->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-400">
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
