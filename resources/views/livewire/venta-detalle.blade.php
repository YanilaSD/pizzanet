
<div>
    {{-- Selección de productos --}}
    <div class="mt-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label for="producto_id" class="module-form-label">
                Producto
            </label>

            <flux:select
                name="producto_id"
                wire:model.live="producto_id"
                :error="$errors->first('producto_id')"
            >
                <option value="">Selecciona un producto</option>

                @forelse ($productosDisponibles as $producto)
                    @php
                        $enCarrito = $detalle[$producto['id']]['cantidad'] ?? 0;
                        $restante = max(($producto['stock'] ?? 0) - $enCarrito, 0);
                        $sinStock = $restante <= 0;
                    @endphp
                    <option
                        value="{{ $producto['id'] }}"
                        @disabled($sinStock)
                    >
                        {{ $producto['nombre'] }} /
                        {{ $producto['categoria'] }} /
                        Bs {{ $producto['precio'] }}
                        @if ($sinStock)
                            — Sin stock
                        @else
                            — Stock: {{ $restante }}
                        @endif
                    </option>
                @empty
                    <option value="">No hay productos disponibles</option>
                @endforelse
            </flux:select>
        </div>

        <div class="md:col-span-1">
            <label for="cantidad" class="module-form-label">
                Cantidad
            </label>

            <flux:input
                type="number"
                name="cantidad"
                wire:model.live="cantidad"
                min="1"
                :disabled="!$producto_id"
                :error="$errors->first('cantidad')"
            />

            <div class="min-h-5 mt-1">
                @if ($producto_id)
                    @php
                        $productoSeleccionado = collect($productosDisponibles)
                            ->firstWhere('id', (int) $producto_id);

                        $stockDisponible = $productoSeleccionado['stock'] ?? 0;
                        $enCarrito = $detalle[$producto_id]['cantidad'] ?? 0;
                        $restante = max($stockDisponible - $enCarrito, 0);
                    @endphp

                    @if ($productoSeleccionado)
                        <p class="text-xs {{ $restante > 0 ? 'text-gray-500 dark:text-gray-400' : 'text-red-500' }}">
                            @if ($restante > 0)
                                Stock disponible: {{ $restante }} de {{ $stockDisponible }}
                            @else
                                Sin stock disponible
                            @endif
                        </p>
                    @endif
                @endif

                @error('cantidad')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror

                @error('producto_id')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="md:col-span-1 flex flex-col">
            <label class="module-form-label">&nbsp;</label>

            <flux:button
                variant="primary"
                color="orange"
                id="btn-agregar"
                wire:click="agregarProducto"
                :disabled="!$this->puedeAgregarProducto()"
                wire:loading.attr="disabled"
                wire:target="agregarProducto"
                icon="plus"
                class="w-full"
            >
                Agregar Producto
            </flux:button>

            <div class="min-h-5 mt-1"></div>
        </div>
    </div>

    {{-- Detalle de la venta --}}
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
                    @forelse ($detalle as $p)
                        <tr class="group hover:bg-orange-50/40 transition-all duration-200 dark:hover:bg-orange-950/20">
                            <td class="px-6 py-4">
                                {{ $p['nombre'] }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $p['cantidad'] }}
                            </td>

                            <td class="px-6 py-4">
                                Bs {{ number_format($p['precio_unitario'], 2) }}
                            </td>

                            <td class="px-6 py-4">
                                Bs {{ number_format($p['subtotal'], 2) }}
                            </td>

                            <td class="px-6 py-4">
                                <button
                                    type="button"
                                    wire:click="eliminarProducto({{ $p['id'] }})"
                                    wire:loading.attr="disabled"
                                    class="text-red-500 hover:text-red-700"
                                >
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

    {{-- Total provisional --}}
    <div class="mt-4 text-center md:text-right text-lg font-semibold">
        Total: Bs {{ number_format($total, 2) }}
    </div>

    {{-- Acciones --}}
    <div class="flex flex-col md:flex-row md:justify-end gap-3 mt-6">
        <flux:button
            class="w-full md:w-auto"
            variant="primary"
            color="red"
            wire:click="cancelarVenta"
            wire:loading.attr="disabled"
        >
            Cancelar Venta
        </flux:button>

        <flux:modal.trigger name="method-pay">
            <flux:button
                :disabled="count($detalle) === 0"
                class="w-full md:w-auto"
            >
                Ir al método de pago
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Modal de confirmación --}}
    <flux:modal
        name="method-pay"
        class="w-full max-w-lg md:w-[480px]"
    >
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    Confirmar venta
                </flux:heading>

                <flux:text class="mt-2">
                    Revisa los descuentos y el método de pago antes de confirmar.
                </flux:text>
            </div>

            {{-- Método de pago --}}
            <div>
                <label class="module-form-label">Tipo de pago</label>

                <select
                    id="tipo_pago_id"
                    wire:model.live="tipo_pago_id"
                    class="block w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
                    <option value="">Selecciona un tipo de pago</option>

                    @forelse ($tipo_pagos as $tipo_pago)
                        <option value="{{ $tipo_pago->id }}">
                            {{ $tipo_pago->nombre }}
                        </option>
                    @empty
                        <option value="" disabled>No hay tipos de pago activos</option>
                    @endforelse
                </select>

                @error('tipo_pago_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Promoción --}}
            <div>
                <label class="module-form-label">Promoción</label>

                <select
                    id="promocion_id"
                    wire:model.live="promocion_id"
                    @disabled($usar_puntos)
                    class="block w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 disabled:opacity-60"
                >
                    <option value="">Sin promoción</option>

                    @forelse ($promociones as $promocion)
                        <option value="{{ $promocion->id }}">
                            {{ $promocion->nombre }} —
                            {{ rtrim(rtrim(number_format($promocion->descuento, 2, '.', ''), '0'), '.') }}% dto.
                            (hasta
                            {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }})
                        </option>
                    @empty
                        <option value="" disabled>No hay promociones activas</option>
                    @endforelse
                </select>

                @error('promocion_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror

                @if ($alerta_compra_minima)
                    <p class="mt-1 text-xs text-red-500">{{ $alerta_compra_minima }}</p>
                @endif

                @if ($usar_puntos)
                    <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                        Para utilizar una promoción, desactiva primero el canje de puntos.
                    </p>
                @endif
            </div>

            {{-- Canje de puntos --}}
            @if ($cliente && ! $cliente->esAnonimo())
                @if ($puede_canjear && $canje_punto)
                    <div class="module-card-nested flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="module-card-label text-green-800 dark:text-green-300">
                                Canje de puntos disponible
                            </p>

                            <p class="module-card-sublabel mt-0.5 text-green-600 dark:text-green-400">
                                El cliente tiene {{ $saldo_puntos }} puntos.
                                Canjear {{ $puntos_canje }}
                                puntos =
                                Bs {{ number_format($descuento_canje, 2) }} de descuento.
                                @if ($saldo_puntos > $puntos_canje)
                                    Quedan {{ $saldo_puntos - $puntos_canje }} puntos, insuficientes para otro canje.
                                @endif
                            </p>

                            @if ($usar_puntos && $puntos_canjeados > 0 && $puntos_canjeados < $puntos_canje)
                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    El subtotal solo cubre {{ $puntos_canjeados }} puntos
                                    (Bs {{ number_format($descuento, 2) }}).
                                    El resto se mantiene en el saldo.
                                </p>
                            @endif
                        </div>

                        <flux:switch
                            wire:model.live="usar_puntos"
                            :disabled="!$puede_canjear || !empty($promocion_id)"
                        />
                    </div>
                @else
                    <div class="module-card-nested px-4 py-3">
                        <p class="module-card-label">
                            El cliente tiene {{ $saldo_puntos }} puntos.
                            No tiene puntos suficientes para realizar el canje.
                        </p>
                    </div>
                @endif

                @if ($promocion_id)
                    <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                        Para canjear puntos, selecciona primero "Sin promoción".
                    </p>
                @endif

                @error('usar_puntos')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            @else
                <div class="module-card-nested px-4 py-3">
                    <p class="module-card-label">
                        Venta sin cliente: no aplica canje de puntos ni acumulación.
                    </p>
                </div>
            @endif

            {{-- Resumen de importes --}}
            <div
                class="module-card-nested space-y-2 px-4 py-3 text-sm"
                wire:key="resumen-{{ $promocion_id }}-{{ $usar_puntos ? '1' : '0' }}-{{ $descuento }}-{{ $total }}"
            >
                <div class="flex justify-between">
                    <span class="module-card-label">Subtotal</span>

                    <span class="text-gray-800 dark:text-gray-200">
                        Bs {{ number_format(collect($detalle)->sum('subtotal'), 2) }}
                    </span>
                </div>

                @if ($descuento > 0)
                    <div class="flex justify-between text-green-600 dark:text-green-400">
                        <span class="module-card-label">
                            Descuento aplicado
                            @if ($promocion_actual)
                                ({{ $promocion_actual->nombre }} {{ rtrim(rtrim(number_format($promocion_actual->descuento, 2, '.', ''), '0'), '.') }}%)
                            @elseif ($usar_puntos)
                                (Canje de {{ $puntos_canjeados }} puntos)
                            @endif
                        </span>

                        <span>
                            - Bs {{ number_format($descuento, 2) }}
                        </span>
                    </div>
                @endif

                <div class="mt-1 flex justify-between border-t border-gray-200 pt-2 font-semibold text-gray-900 dark:border-gray-700 dark:text-white">
                    <span>Total a pagar</span>

                    <span>
                        Bs {{ number_format($total, 2) }}
                    </span>
                </div>
            </div>

            {{-- Confirmar --}}
            <flux:button
                :disabled="!$tipo_pago_id || count($detalle) === 0"
                wire:click="confirmarVenta"
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
