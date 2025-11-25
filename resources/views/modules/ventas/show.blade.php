<x-layouts.app>
    <div class="max-w-md mx-auto bg-white p-4 border border-gray-200 shadow-sm font-mono text-sm" id="recibo">
        <div class="text-center mb-2">
            <h2 class="text-lg font-bold">Pizzería Yuneth</h2>
            <p>{{ $venta->fecha->format('d/m/Y H:i') }}</p>
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
                        <td colspan="4" class="py-1 text-center">No hay productos en esta venta.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <div class="text-right border-t border-gray-300 pt-2">
            <p>Subtotal: ${{ number_format($venta->subtotal, 2) }}</p>
            @if($venta->descuento > 0)
                <p>Descuento: ${{ number_format($venta->descuento, 2) }}</p>
            @endif
            <p class="font-bold">Total: ${{ number_format($venta->total, 2) }}</p>
        </div>

        <div class="text-center mt-2">
            <p>¡Gracias por su compra!</p>
        </div>
    </div>

    <div class="max-w-md mx-auto mt-4 text-center" wire:ignore>
        <flux:button
            type="button"
            variant="primary"
            color="black"
            id="imprimir-recibo-btn"
            class="text-white px-4 py-2 rounded-lg"
            href="{{ route('ventas.index') }}"
        >
            Volver
        </flux:button>
    </div>

    <script>
        function imprimirRecibo() {
    try {
        // Clonar el div del recibo
        const recibo = document.getElementById('recibo').cloneNode(true);

        // Elimina todos los scripts y estilos dentro del div
        recibo.querySelectorAll('script, style').forEach(el => el.remove());

        // Abrir nueva ventana
        const ventana = window.open('', '', 'width=400,height=600');

        // Escribir solo el contenido limpio y un estilo propio
        ventana.document.write(`
            <html>
                <head>
                    <title>Recibo de Venta</title>
                    <style>
                        body { font-family: monospace; padding: 10px; font-size: 12px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border-bottom: 1px solid #000; padding: 4px; }
                        .text-right { text-align: right; }
                        .text-center { text-align: center; }
                    </style>
                </head>
                <body>
                    ${recibo.innerHTML}
               );

        ventana.document.close();
        ventana.focus();
        ventana.print();
        ventana.close();
    } catch (error) {
        console.error('Error al imprimir el recibo:', error);
    }
}

    </script>
    <script>
          // document.addEventListener('livewire:load', function () {
          //     const btn = document.getElementById('imprimir-recibo-btn');
          //     btn.addEventListener('click', function () {
          //         const contenido = document.getElementById('recibo').innerHTML;
          //         const ventana = window.open('', '', 'width=400,height=600');
                  
          //         // ventana.document.close();
          //         ventana.focus();
          //         ventana.print();
          //         ventana.close();
          //     });
        // });
    </script>
</x-layouts.app>
