<x-layouts.app>
    <x-card-header 
        title="Gestión de Festividades"
        description="Registra y gestiona todas las festividades del sistema."
        button-text="Nueva festividad"
        :button-link="route('festividades.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Registro de Festividades"
        description="Listado de festividades registradas"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('festividades.index') }}">
                <flux:input name="search" icon="magnifying-glass" placeholder="Buscar festividad" value="{{ request('search') }}" />
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
            @forelse ($festividades as $festividad)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $festividad->nombre }}
                    </td>
                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        {{ $festividad->descripcion }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:badge color="{{ $festividad->estado == '1' ? 'green' : 'red' }}">
                            {{ $festividad->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>
                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('festividades.edit', $festividad) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>
                        <a href="{{ route('festividades.destroy', $festividad->id) }}"
                           class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                            <flux:icon name="trash" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-400">
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
