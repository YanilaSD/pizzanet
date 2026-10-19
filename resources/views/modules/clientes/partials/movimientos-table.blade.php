<x-data-table
    title="Movimientos de puntos"
    description="Registro de puntos ganados por compras y puntos canjeados"
>
    <x-slot name="head">
        <th>#</th>
        <th>Fecha</th>
        <th>Movimiento</th>
        <th>Detalle</th>
        <th>Puntos</th>
    </x-slot>

    <x-slot name="body">
        @forelse ($movimientos as $movimiento)
            <tr class="module-table-tr">
                <td class="module-table-td-index">
                    {{ $movimientos->firstItem() + $loop->index }}
                </td>

                <td class="module-table-td whitespace-nowrap">
                    {{ \Illuminate\Support\Carbon::parse($movimiento->fecha)->format('d/m/Y H:i') }}
                </td>

                <td class="px-6 py-4">
                    @if ($movimiento->tipo === 'ganado')
                        <flux:badge color="green">Ganado</flux:badge>
                    @else
                        <flux:badge color="orange">Canjeado</flux:badge>
                        @if ((int) $movimiento->estado !== 1)
                            <flux:badge color="zinc">Inactivo</flux:badge>
                        @endif
                    @endif
                </td>

                <td class="module-table-td">
                    @if ($movimiento->tipo === 'ganado')
                        Venta #{{ $movimiento->venta_id }}
                    @else
                        {{ $movimiento->detalle !== '' ? $movimiento->detalle : 'Canje' }}
                        @if ($movimiento->venta_id)
                            · Venta #{{ $movimiento->venta_id }}
                        @endif
                    @endif
                </td>

                <td class="module-table-td font-medium {{ $movimiento->tipo === 'ganado' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $movimiento->tipo === 'ganado' ? '+' : '-' }}{{ $movimiento->puntos }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="module-table-empty">
                    Este cliente no tiene movimientos de puntos.
                </td>
            </tr>
        @endforelse
    </x-slot>

    <x-slot name="pagination">
        {{ $movimientos->links() }}
    </x-slot>
</x-data-table>
