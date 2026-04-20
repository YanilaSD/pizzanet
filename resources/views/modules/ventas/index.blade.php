<x-layouts.app>
    <x-card-header 
        title="Gestión de Ventas"
        description="Registra y gestiona todas las ventas de la pizzería con promociones."
        button-text="Nueva venta"
        :button-link="route('ventas.create')"
    />

    <div class="mt-6 flex gap-6 overflow-x-auto py-2 scrollbar-hide">
        {{-- Card 1 --}}
        <div class="min-w-[320px] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <img
                    src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2340&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="h-20 w-20 rounded-xl object-cover ring-1 ring-gray-200"
                    alt="Pizza Ventas"
                    loading="lazy"
                />

                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-600">Total Ventas</p>
                    <p class="mt-1 truncate text-2xl font-extrabold text-orange-600">
                        Bs {{ number_format($ventasTotal ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $ventasCompletadas ?? 0 }} ventas registradas
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="min-w-[320px] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <img
                    src="https://images.unsplash.com/photo-1655673654158-9f7285b7d1ea?q=80&w=1364&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="h-20 w-20 rounded-xl object-cover ring-1 ring-gray-200"
                    alt="Descuentos"
                    loading="lazy"
                />

                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-600">Descuentos Aplicados</p>
                    <p class="mt-1 truncate text-2xl font-extrabold text-red-600">
                        Bs {{ number_format($descuentoTotal ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">En promociones</p>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="min-w-[320px] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <img
                    src="https://images.unsplash.com/photo-1594007654729-407eedc4be65?auto=format&fit=crop&w=300&q=60"
                    class="h-20 w-20 rounded-xl object-cover ring-1 ring-gray-200"
                    alt="Ventas Completadas"
                    loading="lazy"
                />

                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-600">Ventas Completadas</p>
                    <p class="mt-1 truncate text-2xl font-extrabold text-green-600">
                        {{ $ventasCompletadas ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">Entregadas</p>
                </div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="min-w-[320px] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <img
                    src="https://images.unsplash.com/photo-1571066811602-716837d681de?q=80&w=868&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="h-20 w-20 rounded-xl object-cover ring-1 ring-gray-200"
                    alt="Venta Promedio"
                    loading="lazy"
                />

                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-600">Venta Promedio</p>
                    <p class="mt-1 truncate text-2xl font-extrabold text-blue-600">
                        Bs {{ number_format($ventasPromedio ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">Por transacción</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <x-data-table 
        title="Registro de Ventas"
        description="Historial de todas las ventas realizadas con promociones aplicadas."
    >
        <x-slot name="head">
            <th>ID Venta</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Subtotal</th>
            <th>Descuento</th>
            <th>Total</th>
            <th>Método de Pago</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($ventas as $venta)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition whitespace-nowrap">
                        #{{ $venta->id }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        {{ $venta->cliente->nombre ?? 'Sin nombre' }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                        Bs {{ number_format($venta->subtotal, 2) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-medium text-red-600">
                            - Bs {{ number_format($venta->descuento, 2) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-800 group-hover:text-gray-900 transition whitespace-nowrap">
                        Bs {{ number_format($venta->total, 2) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="lime">
                            {{ $venta->tipoPago->nombre ?? 'N/D' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="{{ $venta->estado == '1' ? 'green' : 'yellow' }}">
                            {{ $venta->estado == '1' ? 'Completada' : 'Pendiente' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a
                                href="{{ route('ventas.show', $venta) }}"
                                class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition"
                                title="Ver"
                                aria-label="Ver venta #{{ $venta->id }}"
                            >
                                <flux:icon name="eye" />
                            </a>

                            <form
                                action="{{ route('ventas.destroy', $venta) }}"
                                method="POST"
                                onsubmit="return confirm('¿Eliminar la venta #{{ $venta->id }}? Esta acción no se puede deshacer.')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition"
                                    title="Eliminar"
                                    aria-label="Eliminar venta #{{ $venta->id }}"
                                >
                                    <flux:icon name="trash" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-8 text-gray-400">
                        No se han registrado ventas aún.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $ventas->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>