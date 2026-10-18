<x-layouts.app>
    <x-card-header 
        title="Gestión de Tipos de Pago"
        description="Registra y gestiona todos los tipos de pago del sistema."
        button-text="Nuevo tipo de pago"
        :button-link="route('tipo_pagos.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
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
            <th>pago habilitado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($tipo_pagos as $tipo_pago)
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->index + 1 }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $tipo_pago->nombre }}
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $tipo_pago->activo == 1 ? 'green' : 'red' }}">
                            {{ $tipo_pago->activo == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="module-table-td-actions">
                        <a href="{{ route('tipo_pagos.edit', $tipo_pago) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>
                            
                         <a href="{{ route('tipo_pagos.destroy', $tipo_pago) }}"
                               class="module-icon-btn-red">
                                <flux:icon name="trash" />
                         </a>

                        <a href="{{ route('tipo_pagos.toggle', $tipo_pago) }}"
                           class="module-icon-btn-red">
                            <flux:icon name="{{ $tipo_pago->activo == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="module-table-empty">
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
