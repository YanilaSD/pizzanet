<x-data-table
    title="Historial de Canjes"
    description="Registro de canjes realizados por el cliente"
>
    <x-slot name="head">
        <th>#</th>
        <th>Fecha</th>
        <th>Descuento</th>
        <th>Puntos Canjeados</th>
        <th>Monto (Bs)</th>
        <th>Venta</th>
        <th>Estado</th>
    </x-slot>

    <x-slot name="body">
        @forelse ($canjes as $canje)
            <tr class="module-table-tr">
                <td class="module-table-td-index">
                    {{ $loop->iteration }}
                </td>

                <td class="module-table-td whitespace-nowrap">
                    {{ optional($canje->fecha)->format('d/m/Y') }}
                </td>

                <td class="module-table-td-primary">
                    {{ $canje->descuento->nombre ?? 'N/D' }}
                </td>

                <td class="module-table-td">
                    {{ $canje->puntos }}
                </td>

                <td class="module-table-td">
                    Bs {{ number_format($canje->montoDescuento(), 2) }}
                </td>

                <td class="module-table-td">
                    {{ $canje->venta_id ? '#' . $canje->venta_id : 'N/A' }}
                </td>

                <td class="px-6 py-4">
                    <flux:badge color="{{ $canje->estado == 1 ? 'green' : 'red' }}">
                        {{ $canje->estado == 1 ? 'Activo' : 'Inactivo' }}
                    </flux:badge>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="module-table-empty">
                    Este cliente no tiene canjes registrados.
                </td>
            </tr>
        @endforelse
    </x-slot>

    <x-slot name="pagination">
        {{ $canjes->links() }}
    </x-slot>
</x-data-table>
