<x-layouts.app>
    <x-card-header 
        title="Gestión de Categorías"
        description="Registra y gestiona todos los categorias del sistema."
        button-text="Nueva categoria"
        :button-link="route('categorias.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Categoria"
        description="Listado de las categorias registradas"
    >
        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($categorias as $categoria)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->index + 1 }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $categoria->nombre }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $categoria->estado == 1 ? 'green' : 'red' }}">
                            {{ $categoria->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('categorias.edit', $categoria) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>
                        @if($categoria->estado == '1')
                            <a href="{{ route('categorias.destroy', $categoria) }}"
                               class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                                <flux:icon name="trash" />
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-400">
                        No hay categorias registradas en el sistema.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $categorias->links() }}
        </x-slot>
    </x-data-table>

</x-layouts.app>
