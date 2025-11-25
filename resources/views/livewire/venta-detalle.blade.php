<div>
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

        <flux:button
            variant="primary" color="red"
            wire:click="cancelarVenta"
        >
            Cancelar Venta
        </flux:button>

        <flux:modal.trigger name="method-pay">
            <flux:button :disabled="count($detalle) === 0">Ir al método de pago</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:modal name="method-pay" :disabled="count($detalle) === 0" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirmar ventas</flux:heading>
                <flux:text class="mt-2">Ya falta poco para completar tu compra.</flux:text>
            </div>

            <div class="w-full">
                <label for="tipo_pago_id" class="block text-sm font-medium text-gray-700">Tipo de pago</label>
                <flux:select
                    name="tipo_pago_id"
                    wire:model="tipo_pago_id"
                    wire:change="$refresh"
                    :error="$errors->first('tipo_pago_id')"
                >
                    <option value="">Selecciona un tipo de pago</option>
                    @foreach ($tipo_pagos as $tipo_pago)
                        <option value="{{ $tipo_pago->id }}">
                            {{ $tipo_pago->nombre }}
                        </option>
                    @endforeach

                </flux:select>

            </div>

            <div class="w-full">
                <label for="promocion_id" class="block text-sm font-medium text-gray-700">Descuento</label>
                <flux:select
                    name="promocion_id"
                    wire:model="promocion_id"
                    wire:change="$refresh"
                    :error="$errors->first('promocion_id')"
                >
                    <option value="">Ninguna</option>

                    @php $tieneOpciones = false; @endphp

                    @foreach ($promociones as $promocion)
                        @if($promocion->descuento > 0)
                            <option value="{{ $promocion->id }}">
                                {{ $promocion->nombre }}: Bs {{ $promocion->descuento }}
                            </option>
                            @php $tieneOpciones = true; @endphp
                        @endif
                    @endforeach

                    @if(session('cliente') && isset(session('cliente')['descuento']) && session('cliente')['descuento'] > 0)
                        <option value="descuento">
                            Descuento especial: Bs {{ session('cliente')['descuento'] }}
                        </option>
                        @php $tieneOpciones = true; @endphp
                    @endif

                    @unless($tieneOpciones)
                        <option value="">Sin promoción activa</option>
                    @endunless
                </flux:select>


                <div class="mt-4">
                    <flux:callout variant="secondary" icon="information-circle" heading="Subtotal de la venta: Bs {{ number_format(collect($detalle)->sum('subtotal'), 2) }}" />
                </div>
            </div>


            <div class="flex">
                <flux:spacer />

                <flux:button
                    :disabled="!$tipo_pago_id || count($detalle) === 0"
                    wire:click="confirmarVenta()"
                    class="w-full"
                    type="button"
                    color="green"
                    variant="primary"
                >
                    Completar venta por <strong> Bs {{ number_format($total, 2) }}</strong>
                </flux:button>

            </div>
        </div>
    </flux:modal>
    
</div>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('validarBoton', () => {
            console.log('Validando botón Agregar Producto');
            const producto = document.querySelector('[wire\\:model="producto_id"]').value;
            const cantidad = document.querySelector('[wire\\:model="cantidad"]').value;
            document.getElementById('btn-agregar').disabled = !(producto && cantidad > 0);
        });
    });
</script>
