<x-layouts.app>
    <x-card-header 
        title="Gestión de Categorías"
        description="Registra y gestiona todos los categorias del sistema."
        button-text="Nueva categoria"
        :button-link="route('categorias.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
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
            <th>Descripción</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($categorias as $categoria)
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->index + 1 }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $categoria->nombre }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $categoria->descripcion ?? 'Sin descripción' }}
                    </td>

                    <td class="module-table-td-actions">
                        <a href="{{ route('categorias.edit', $categoria) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>
                        @if($categoria->estado == '1')
                            <a href="{{ route('categorias.destroy', $categoria) }}"
                               class="module-icon-btn-red">
                                <flux:icon name="trash" />
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="module-table-empty">
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
