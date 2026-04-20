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
            <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                <td class="px-6 py-4 text-gray-500">
                    {{ $loop->iteration }}
                </td>

                <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                    {{ optional($canje->fecha)->format('d/m/Y') }}
                </td>

                <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                    {{ $canje->descuento->nombre ?? 'N/D' }}
                </td>

                <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                    {{ $canje->puntos }}
                </td>

                <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                    Bs {{ number_format($canje->descuento->descuento ?? 0, 2) }}
                </td>

                <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
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
                <td colspan="7" class="text-center py-8 text-gray-400">
                    Este cliente no tiene canjes registrados.
                </td>
            </tr>
        @endforelse
    </x-slot>

    <x-slot name="pagination">
        {{ $canjes->links() }}
    </x-slot>
</x-data-table>
