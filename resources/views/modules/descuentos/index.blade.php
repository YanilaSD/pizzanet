<x-layouts.app>
    <x-card-header 
        title="Gestión de Descuentos"
        description="Administra los descuentos por puntos del sistema"
        button-text="Nuevo descuento"
        :button-link="route('descuentos.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl my-4">
            <strong>¡Éxito!</strong>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Descuentos Activos"
        description="Solo debe existir un descuento activo a la vez"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('descuentos.index') }}">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Buscar descuento"
                    value="{{ request('search') }}"
                />
            </form>
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
            @forelse ($descuentos as $descuento)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $descuento->nombre }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        {{ $descuento->descripcion }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        {{ $descuento->puntos }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        Bs {{ number_format($descuento->descuento, 2) }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $descuento->estado == 1 ? 'green' : 'red' }}">
                            {{ $descuento->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('descuentos.edit', $descuento) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('descuentos.destroy', $descuento) }}"
                           class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                            <flux:icon name="trash" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-400">
                        No se encontraron descuentos activos.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $descuentos->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>
