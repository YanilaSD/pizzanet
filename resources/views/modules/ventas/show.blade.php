<x-layouts.app>
    <x-module-card
        class="mx-auto max-w-md font-mono text-sm"
        id="recibo"
    >
{{-- ENCABEZADO --}}
<div class="text-center mb-3">

    @if(
        $venta->cliente &&
        filled($venta->cliente->nombre) &&
        mb_strtolower(trim($venta->cliente->nombre)) !== 'sin nombre' &&
        filled($venta->cliente->ci) &&
        $venta->cliente->ci !== '99999999'
    )
        {{-- CLIENTE REGISTRADO --}}
        <h2 class="text-sm font-semibold italic tracking-wide">
            Pizzería Yuneth
        </h2>

        <p class="mt-2 text-lg font-bold">
            Pedido N.º {{ str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}
        </p>

        <p class="mt-1 text-sm font-semibold">
            Cliente: {{ $venta->cliente->nombre }}
        </p>

    @else
        {{-- CLIENTE SIN NOMBRE --}}
        <h2 class="text-xl font-bold">
            Pizzería Yuneth
        </h2>

        <p class="mt-2 text-2xl font-extrabold">
            PEDIDO N.º {{ str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}
        </p>

        <p class="mt-1 text-sm font-bold uppercase">
            Sin nombre
        </p>
    @endif

          {{-- NOMBRE DEL CAJERO --}}
    <p class="mt-1 text-xs">
        Atendido por: {{ auth()->user()->name ?? 'No identificado' }}
    </p>

</div>



         {{-- ENLACE DE PUNTOS: SOLO CLIENTES REGISTRADOS --}}
            @if(
                $venta->cliente &&
                filled($venta->cliente->nombre) &&
                mb_strtolower(trim($venta->cliente->nombre)) !== 'sin nombre'
            )
                <div class="text-center mt-4 border-t border-gray-300 pt-3">
                    <p class="font-bold">
                        ¡Consulta tus puntos y beneficios!
                    </p>
                </div>
            @endif


        {{-- DETALLE DE PRODUCTOS --}}
        <table class="w-full border-t border-b border-gray-300 mb-2 text-left">
            <thead>
                <tr>
                    <th class="border-b border-gray-300 py-1 text-left">
                        Producto
                    </th>
                    <th class="border-b border-gray-300 py-1 text-center">
                        Cant.
                    </th>
                    <th class="border-b border-gray-300 py-1 text-right">
                        Precio
                    </th>
                    <th class="border-b border-gray-300 py-1 text-right">
                        Subtotal
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse($venta->detalleVentas as $detalle)
                    <tr>
                        <td class="py-1">
                            {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                        </td>

                        <td class="py-1 text-center">
                            {{ $detalle->cantidad }}
                        </td>

                        <td class="py-1 text-right whitespace-nowrap">
                            Bs {{ number_format($detalle->precio_unitario, 2, ',', '.') }}
                        </td>

                        <td class="py-1 text-right whitespace-nowrap">
                            Bs {{ number_format($detalle->subtotal, 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center">
                            No hay productos en esta venta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- SUBTOTAL, DESCUENTO Y TOTAL --}}
        <div class="text-right border-t border-gray-300 pt-2">
            <p>
                Subtotal:
                Bs {{ number_format($venta->subtotal, 2, ',', '.') }}
            </p>

            @if($venta->descuento > 0)
                <p>
                    Descuento:
                    Bs {{ number_format($venta->descuento, 2, ',', '.') }}
                </p>
            @endif

            <p class="font-bold text-lg mt-1">
                TOTAL: Bs {{ number_format($venta->total, 2, ',', '.') }}
            </p>
              {{-- MÉTODO DE PAGO DEBAJO DEL TOTAL 
           <p class="mt-1 text-xs">
                Método de pago:
                {{ $venta->tipoPago->nombre ?? 'No especificado' }}
            </p> 
            --}}
        </div>


        {{-- MENSAJE FINAL DE PIZZERÍA YUNETH --}}
    <div class="text-center mt-4 italic">
        <p class="font-bold text-sm">
            Antojos felices, sonrisas infinitas.
        </p>

        <p class="mt-1 text-xs">
            ¡Una porción de felicidad en cada mordida,
            así sabe Yuneth!
        </p>

         
</div>
    </x-module-card>

    {{-- BOTONES --}}
    <div class="flex flex-wrap gap-2 mt-4 no-print">
        <flux:button
            type="button"
            variant="primary"
            color="orange"
            onclick="imprimirRecibo()"
            class="px-4 py-2 rounded-lg"
        >
            🖨️ Imprimir ticket
        </flux:button>

        <flux:button
            variant="primary"
            href="{{ route('ventas.index') }}"
            class="px-4 py-2 rounded-lg"
        >
            Volver
        </flux:button>
    </div>

    {{-- ESTILOS --}}
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            html,
            body {
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body * {
                visibility: hidden;
            }

            #recibo,
            #recibo * {
                visibility: visible;
            }

            #recibo {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                max-width: 80mm !important;
                min-width: 0 !important;
                margin: 0 !important;
                padding: 2mm !important;
                box-sizing: border-box !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                font-size: 10px !important;
                overflow: visible !important;
            }

            #recibo h2 {
                font-size: 16px !important;
            }

            #recibo table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
                font-size: 9px !important;
            }

            #recibo th,
            #recibo td {
                padding: 3px 1px !important;
                overflow-wrap: anywhere !important;
                word-break: normal !important;
            }

            #recibo th:nth-child(1),
            #recibo td:nth-child(1) {
                width: 36% !important;
            }

            #recibo th:nth-child(2),
            #recibo td:nth-child(2) {
                width: 12% !important;
            }

            #recibo th:nth-child(3),
            #recibo td:nth-child(3) {
                width: 24% !important;
            }

            #recibo th:nth-child(4),
            #recibo td:nth-child(4) {
                width: 28% !important;
            }

            #recibo .whitespace-nowrap {
                white-space: normal !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    {{-- FUNCIÓN DE IMPRESIÓN --}}
    <script>
        function imprimirRecibo() {
            window.print();
        }
    </script>
</x-layouts.app>