<div>
    <div class="mt-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-1">
            <label for="producto_id" class="module-form-label">Producto</label>
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

        <div class="md:col-span-2">
            <label for="cantidad" class="module-form-label">Cantidad</label>
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
                class="w-full md:w-auto"
            >
                Agregar Producto
            </flux:button>
        </div>
    </div>

    <x-module-card-lg class="mt-4 !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-[700px] w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Cantidad</th>
                        <th class="px-6 py-3">Precio Unitario</th>
                        <th class="px-6 py-3">Subtotal</th>
                        <th class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($detalle as $p)
                        <tr class="group hover:bg-orange-50/40 transition-all duration-200 dark:hover:bg-orange-950/20">
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
    </x-module-card-lg>

    <div class="mt-4 text-center md:text-right text-lg font-semibold">
        Total: Bs {{ number_format($total, 2) }}
    </div>

    <div class="flex flex-col md:flex-row md:justify-end gap-3 mt-6">
        <flux:button class="w-full md:w-auto" variant="primary" color="red" wire:click="cancelarVenta">
            Cancelar Venta
        </flux:button>

        <flux:modal.trigger name="method-pay">
            <flux:button :disabled="count($detalle) === 0">
                Ir al método de pago
            </flux:button>
        </flux:modal.trigger>
    </div>

    <flux:modal
        name="method-pay"
        class="w-full max-w-lg md:w-[480px]"
    >
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirmar venta</flux:heading>
                <flux:text class="mt-2">Revisa los descuentos y el método de pago antes de confirmar.</flux:text>
            </div>

            <div class="md:col-span-1">
                <label class="module-form-label">Tipo de pago</label>
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

            <div class="md:col-span-1">
                <label class="module-form-label">Promoción</label>
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

            @php
                $clienteModel = \App\Models\Cliente::find($cliente->id);
            @endphp
            @if($clienteModel->id != 1)
                @if($puede_canjear)
                    <div class="module-card-nested flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="module-card-label text-green-800 dark:text-green-300">Canje de puntos disponible</p>
                            <p class="module-card-sublabel mt-0.5 text-green-600 dark:text-green-400">
                                El cliente tiene {{ $saldo_puntos }} puntos —
                                canjear 100 pts = Bs {{ number_format($descuento_canje, 2) }} de descuento
                            </p>
                        </div>
                        <flux:switch wire:model.live="usar_puntos" />
                    </div>
                @else
                    <div class="module-card-nested px-4 py-3">
                        <p class="module-card-label">
                            @if(session('cliente'))
                                El cliente tiene {{ $saldo_puntos }} puntos —
                                necesita 100 para canjear Bs {{ number_format($descuento_canje, 2) }}
                            @else
                                Venta sin cliente — no aplica canje de puntos
                            @endif
                        </p>
                    </div>
                @endif
            @endif

            <div class="module-card-nested space-y-1 px-4 py-3 text-sm">
                <div class="flex justify-between">
                    <span class="module-card-label">Subtotal</span>
                    <span class="text-gray-800 dark:text-gray-200">Bs {{ number_format(collect($detalle)->sum('subtotal'), 2) }}</span>
                </div>
                @if($descuento > 0)
                    <div class="flex justify-between text-green-600 dark:text-green-400">
                        <span class="module-card-label">Descuento aplicado</span>
                        <span>- Bs {{ number_format($descuento, 2) }}</span>
                    </div>
                @endif
                <div class="mt-1 flex justify-between border-t border-gray-200 pt-2 font-semibold text-gray-900 dark:border-gray-700 dark:text-white">
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