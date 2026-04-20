<x-layouts.app>
    <div class="max-w-md mx-auto bg-white p-4 border border-gray-200 shadow-sm font-mono text-sm" id="recibo">
        
        <div class="text-center mb-2">
            <h2 class="text-lg font-bold">Pizzería Yuneth</h2>
            <p>Cliente: {{ $venta->cliente->nombre ?? 'Sin nombre' }}</p>
            <p>Método de pago: {{ $venta->tipoPago->nombre }}</p>
        </div>

        <table class="w-full border-t border-b border-gray-300 mb-2 text-left">
            <thead>
                <tr>
                    <th class="border-b border-gray-300 py-1 text-left">Producto</th>
                    <th class="border-b border-gray-300 py-1 text-center">Cant</th>
                    <th class="border-b border-gray-300 py-1 text-right">Precio</th>
                    <th class="border-b border-gray-300 py-1 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venta->detalleVentas as $detalle)
                    <tr>
                        <td class="py-1">{{ $detalle->producto->nombre }}</td>
                        <td class="py-1 text-center">{{ $detalle->cantidad }}</td>
                        <td class="py-1 text-right">Bs {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="py-1 text-right">Bs {{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-1 text-center">
                            No hay productos en esta venta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-right border-t border-gray-300 pt-2">
            <p>Subtotal: Bs {{ number_format($venta->subtotal, 2) }}</p>

            @if($venta->descuento > 0)
                <p>Descuento: Bs {{ number_format($venta->descuento, 2) }}</p>
            @endif

            <p class="font-bold text-base">
                Total: Bs {{ number_format($venta->total, 2) }}
            </p>
        </div>

        <div class="text-center mt-2">
            <p>¡Gracias por su compra!</p>
        </div>

    </div>

    <div class="max-w-md mx-auto mt-4 flex justify-center gap-3" wire:ignore>

        <flux:button
            type="button"
            variant="primary"
            color="orange"
            onclick="imprimirRecibo()"
            class="px-4 py-2 rounded-lg"
        >
            🖨️ Imprimir
        </flux:button>

        <flux:button
            variant="primary"
            href="{{ route('ventas.index') }}"
            class="px-4 py-2 rounded-lg"
        >
            Volver
        </flux:button>

    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #recibo, #recibo * {
                visibility: visible;
            }

            #recibo {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <script>
        function imprimirRecibo() {
            window.print();
        }
    </script>

</x-layouts.app>
