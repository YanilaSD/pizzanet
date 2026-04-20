<x-layouts.app>
    <x-card-header 
        title="Gestión de Tipos de Pago"
        description="Registra y gestiona todos los tipos de pago del sistema."
        button-text="Nuevo tipo de pago"
        :button-link="route('tipo_pagos.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Tipos de Pago"
        description="Listado de los tipos de pago registrados"
    >
        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($tipo_pagos as $tipo_pago)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->index + 1 }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $tipo_pago->nombre }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $tipo_pago->estado == 1 ? 'green' : 'red' }}">
                            {{ $tipo_pago->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('tipo_pagos.edit', $tipo_pago) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('tipo_pagos.toggle', $tipo_pago) }}"
                           class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                            <flux:icon name="{{ $tipo_pago->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-400">
                        No hay tipos de pago registrados en el sistema.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $tipo_pagos->links() }}
        </x-slot>
    </x-data-table>

</x-layouts.app>
