<x-layouts.app>
    @if(session('ventas'))
        <flux:callout icon="check" class="mb-2" variant="success" inline>
            <flux:callout.heading>{{ session('ventas') }}</flux:callout.heading>
        </flux:callout>
    @endif
    <x-card-header 
        title="Gestión de Ventas"
        description="Registra y gestiona todas las ventas de la pizzería con promociones."
        button-text="Nueva venta"
        :button-link="route('ventas.create')"
    />

    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-4 xl:grid-cols-4">

        <x-module-card class="relative h-48 overflow-hidden rounded-2xl border-0 shadow-lg group">
            <img
                src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2340&auto=format&fit=crop"
                alt="Ventas"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-black/20"></div>

            <div class="relative z-10 flex h-full flex-col justify-end p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-300">
                    Total Ventas
                </p>

                <p class="mt-2 text-3xl font-extrabold text-white">
                    Bs {{ number_format($ventasTotal ?? 0, 2) }}
                </p>

                <p class="mt-1 text-sm text-gray-200">
                    {{ $ventasCompletadas ?? 0 }} ventas registradas
                </p>
            </div>
        </x-module-card>

        <x-module-card class="relative h-48 overflow-hidden rounded-2xl border-0 shadow-lg group">
            <img
                src="https://images.unsplash.com/photo-1655673654158-9f7285b7d1ea?q=80&w=1364&auto=format&fit=crop"
                alt="Descuentos"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-black/20"></div>

            <div class="relative z-10 flex h-full flex-col justify-end p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-red-300">
                    Descuentos Aplicados
                </p>

                <p class="mt-2 text-3xl font-extrabold text-white">
                    Bs {{ number_format($descuentoTotal ?? 0, 2) }}
                </p>

                <p class="mt-1 text-sm text-gray-200">
                    Promociones y ofertas
                </p>
            </div>
        </x-module-card>

        <x-module-card class="relative h-48 overflow-hidden rounded-2xl border-0 shadow-lg group">
            <img
                src="https://images.unsplash.com/photo-1594007654729-407eedc4be65?auto=format&fit=crop&w=1200&q=80"
                alt="Ventas completadas"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-black/20"></div>

            <div class="relative z-10 flex h-full flex-col justify-end p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-green-300">
                    Ventas Completadas
                </p>

                <p class="mt-2 text-3xl font-extrabold text-white">
                    {{ $ventasCompletadas ?? 0 }}
                </p>

                <p class="mt-1 text-sm text-gray-200">
                    Pedidos entregados
                </p>
            </div>
        </x-module-card>

        <x-module-card class="relative h-48 overflow-hidden rounded-2xl border-0 shadow-lg group">
            <img
                src="https://images.unsplash.com/photo-1571066811602-716837d681de?q=80&w=868&auto=format&fit=crop"
                alt="Venta promedio"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-black/20"></div>

            <div class="relative z-10 flex h-full flex-col justify-end p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-300">
                    Venta Promedio
                </p>

                <p class="mt-2 text-3xl font-extrabold text-white">
                    Bs {{ number_format($ventasPromedio ?? 0, 2) }}
                </p>

                <p class="mt-1 text-sm text-gray-200">
                    Por transacción
                </p>
            </div>
        </x-module-card>

    </div>

    <x-data-table 
        title="Registro de Ventas"
        description="Historial de todas las ventas realizadas con promociones aplicadas."
    >
        <x-slot name="head">
            <th>#</th>
            <th>Cliente</th>
            <th>Importe</th>
            <th>Tipo de Pago</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($ventas as $venta)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold text-gray-900">
                            #{{ $venta->id }}
                        </span>
                        <p>
                            <div>
                            <p class="font-medium text-gray-800 text-xs">
                                {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                            </p>

                            <p class="text-xs text-gray-700">
                                {{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}
                            </p>
                        </div>
                        </p>
                    </td>

                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-800 text-xs uppercase">
                                {{ $venta->cliente->nombre ?? 'SIN NOMBRE' }}
                            </p>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span>
                            Bs {{ number_format($venta->total, 2) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="sky">
                            {{ $venta->tipoPago->nombre ?? 'N/D' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge
                            color="{{ $venta->estado == '1' ? 'green' : 'yellow' }}"
                        >
                            {{ $venta->estado == '1' ? 'Completada' : 'Pendiente' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition">

                            <a
                                href="{{ route('ventas.show', $venta) }}"
                                class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition"
                                title="Ver"
                            >
                                <flux:icon name="eye" />
                            </a>

                            <form
                                action="{{ route('ventas.destroy', $venta) }}"
                                method="POST"
                                onsubmit="return confirm('¿Eliminar la venta #{{ $venta->id }}? Esta acción no se puede deshacer.')"
                            >
                                @csrf
                                @method('POST')

                                <button
                                    type="submit"
                                    class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition"
                                    title="Eliminar"
                                >
                                    <flux:icon name="x-circle" />
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-gray-400">
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