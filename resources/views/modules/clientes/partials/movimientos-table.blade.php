<x-data-table
    title="Movimientos de puntos"
    description="Registro de puntos ganados, canjeados y revertidos"
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
            @php
                $esRevertidoCanje = $movimiento->tipo === 'revertido' && ($movimiento->origen ?? '') === 'canje';
                $positivo = $movimiento->tipo === 'ganado' || $esRevertidoCanje;
            @endphp
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
                    @elseif ($movimiento->tipo === 'revertido')
                        <flux:badge color="red">Revertido</flux:badge>
                    @else
                        <flux:badge color="orange">Canjeado</flux:badge>
                    @endif
                </td>

                <td class="module-table-td">
                    @if ($movimiento->tipo === 'ganado')
                        Venta #{{ $movimiento->venta_id }}
                    @elseif ($movimiento->tipo === 'revertido')
                        Baja de venta
                        @if ($movimiento->venta_id)
                            #{{ $movimiento->venta_id }}
                        @endif
                        @if ($esRevertidoCanje)
                            · canje
                            @if ($movimiento->detalle !== '')
                                {{ $movimiento->detalle }}
                            @endif
                        @else
                            · puntos ganados
                        @endif
                    @else
                        {{ $movimiento->detalle !== '' ? $movimiento->detalle : 'Canje' }}
                        @if ($movimiento->venta_id)
                            · Venta #{{ $movimiento->venta_id }}
                        @endif
                    @endif
                </td>

                <td class="module-table-td font-medium {{ $positivo ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $positivo ? '+' : '-' }}{{ $movimiento->puntos }}
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
