<div>
    {{-- Agregar producto --}}
    <div class="mt-4 flex gap-4 my-4">
        <div class="w-full">
            <label for="producto_id" class="block text-sm font-medium text-gray-700">Producto</label>
            <flux:select
                name="producto_id"
                wire:model="producto_id"
                :error="$errors->first('producto_id')"
            >
                <option value="">Selecciona un producto</option>
                @foreach ($productosDisponibles as $producto)
                    <option value="{{ $producto->id }}">
                        {{ $producto->nombre }} / {{ $producto->categoria->nombre }} / Bs {{ $producto->precio }}
                    </option>
                @endforeach
            </flux:select>
        </div>

        <div class="w-full">
            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad</label>
            <flux:input
                type="number"
                name="cantidad"
                wire:model="cantidad"
                min="1"
                :error="$errors->first('cantidad')"
            />
        </div>

        <div class="flex items-end">
            <flux:button
                variant="primary" color="orange"
                id="btn-agregar"
                wire:click="agregarProducto"
                wire:loading.attr="disabled"
                wire:target="agregarProducto"
                icon="plus"
            >
                Agregar Producto
            </flux:button>
        </div>
    </div>

    {{-- Tabla del detalle --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Producto</th>
                    <th class="px-6 py-3">Cantidad</th>
                    <th class="px-6 py-3">Precio Unitario</th>
                    <th class="px-6 py-3">Subtotal</th>
                    <th class="px-6 py-3">Acción</th>
                </tr>
            </thead>
            <tbody class="bg-gray-100 border-b">
                @forelse($detalle as $p)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">{{ $p['nombre'] }}</td>
                        <td class="px-6 py-4">{{ $p['cantidad'] }}</td>
                        <td class="px-6 py-4">Bs {{ number_format($p['precio_unitario'], 2) }}</td>
                        <td class="px-6 py-4">Bs {{ number_format($p['subtotal'], 2) }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="eliminarProducto({{ $p['id'] }})"
                                class="text-red-500 hover:text-red-700">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Agrega productos a esta venta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Total --}}
    <div class="mt-4 text-right text-lg font-semibold">
        Total: Bs {{ number_format($total, 2) }}
    </div>

    <div class="flex justify-end gap-4 mt-6">
        <flux:button variant="primary" color="red" wire:click="cancelarVenta">
            Cancelar Venta
        </flux:button>

        <flux:modal.trigger name="method-pay">
            <flux:button :disabled="count($detalle) === 0">
                Ir al método de pago
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Modal de pago --}}
    <flux:modal name="method-pay" class="md:w-[480px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirmar venta</flux:heading>
                <flux:text class="mt-2">Revisa los descuentos y el método de pago antes de confirmar.</flux:text>
            </div>

            {{-- Tipo de pago --}}
            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de pago</label>
                <flux:select
                    name="tipo_pago_id"
                    wire:model="tipo_pago_id"
                    wire:change="$refresh"
                    :error="$errors->first('tipo_pago_id')"
                >
                    <option value="">Selecciona un tipo de pago</option>
                    @foreach ($tipo_pagos as $tipo_pago)
                        <option value="{{ $tipo_pago->id }}">{{ $tipo_pago->nombre }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">Promoción</label>
                <flux:select
                    name="promocion_id"
                    wire:model="promocion_id"
                    wire:change="$refresh"
                    :error="$errors->first('promocion_id')"
                >
                    <option value="">Sin promoción</option>
                    @foreach ($promociones as $promocion)
                        <option value="{{ $promocion->id }}">
                            {{ $promocion->nombre }} — {{ $promocion->descuento }}% dto.
                            (hasta {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }})
                        </option>
                    @endforeach
                </flux:select>
            </div>

            @if($puede_canjear)
                <div class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-green-800">Canje de puntos disponible</p>
                        <p class="text-xs text-green-600 mt-0.5">
                            El cliente tiene {{ $saldo_puntos }} puntos —
                            canjear 100 pts = Bs {{ number_format($descuento_canje, 2) }} de descuento
                        </p>
                    </div>
                    <flux:switch wire:model.live="usar_puntos" />
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                    <p class="text-sm text-gray-500">
                        @if(session('cliente'))
                            El cliente tiene {{ $saldo_puntos }} puntos —
                            necesita 100 para canjear Bs {{ number_format($descuento_canje, 2) }}
                        @else
                            Venta sin cliente — no aplica canje de puntos
                        @endif
                    </p>
                </div>
            @endif

            {{-- Resumen de totales --}}
            <div class="rounded-lg bg-gray-50 border border-gray-200 px-4 py-3 space-y-1 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Bs {{ number_format(collect($detalle)->sum('subtotal'), 2) }}</span>
                </div>
                @if($descuento > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Descuento aplicado</span>
                        <span>- Bs {{ number_format($descuento, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-semibold text-gray-900 border-t border-gray-200 pt-2 mt-1">
                    <span>Total a pagar</span>
                    <span>Bs {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <flux:button
                :disabled="!$tipo_pago_id || count($detalle) === 0"
                wire:click="confirmarVenta()"
                wire:loading.attr="disabled"
                wire:target="confirmarVenta"
                class="w-full"
                type="button"
                color="green"
                variant="primary"
            >
                Completar venta — Bs {{ number_format($total, 2) }}
            </flux:button>
        </div>
    </flux:modal>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('validarBoton', () => {
        const producto = document.querySelector('[wire\\:model="producto_id"]').value;
        const cantidad = document.querySelector('[wire\\:model="cantidad"]').value;
        document.getElementById('btn-agregar').disabled = !(producto && cantidad > 0);
    });
});
</script>