<x-layouts.app>
    <div class="grid grid-cols-4 gap-6">
        <div class="col-span-3">
            <div class="flex justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">Gestión de Ventas</h1>
                    <p>Registra y gestiona todas las ventas de la pizzería con promociones</p>
                </div>
            </div>
        </div>
        <div class="col-span-1">
            <p>Usuario: {{ auth()->user()->name }}</p>
            <p>Fecha: {{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('d/m/Y') }}</p>
        </div>
    </div>

    <flux:separator />

    {{-- Búsqueda de cliente --}}
    <form action="{{ route('ventas.searchClient') }}" method="post">
        @csrf
        <div class="flex-1 max-md:py-6 self-stretch my-4">
            <flux:heading size="xl" level="1">Datos del cliente</flux:heading>
            <flux:text class="mb-6 mt-2 text-base">
                Ingresa la información del cliente para continuar con la venta.
            </flux:text>
        </div>

        @if(session('cliente_no_encontrado'))
            <flux:callout icon="exclamation-triangle" variant="danger" inline>
                <flux:callout.heading>{{ session('cliente_no_encontrado') }}</flux:callout.heading>
                <x-slot name="actions">
                    <flux:button
                        type="button"
                        onclick="window.location.href='{{ route('clientes.create') }}'"
                    >
                        Registrar Cliente
                    </flux:button>
                </x-slot>
            </flux:callout>
        @endif

        <div class="grid grid-cols-5 gap-6 my-4">
            <div class="col-span-3 ">
                <label for="ci" class="block text-sm font-medium text-gray-700 dark:text-gray-400 ">Cliente</label>
                <flux:input.group>
                    <flux:input name="ci" placeholder="Ingresa CI del cliente" />
                    <flux:button type="submit" name="action" value="buscar" icon="magnifying-glass">
                        Buscar
                    </flux:button>
                    <flux:button type="submit" name="action" value="sin_cliente" variant="primary" color="orange" icon="shopping-cart">
                        Venta sin cliente
                    </flux:button>
                </flux:input.group>
            </div>
        </div>
    </form>

    {{-- Datos del cliente encontrado --}}
    @if(!is_null($cliente))
        <div class="my-4">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">CI</th>
                            <th class="px-6 py-3">Celular</th>
                            <th class="px-6 py-3">Puntos acumulados</th>
                            <th class="px-6 py-3">Estado canje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $clienteModel = \App\Models\Cliente::find($cliente['id']);
                            $saldo = $clienteModel?->saldo_puntos ?? 0;
                            $puedeCanjer = $saldo >= 100;
                        @endphp
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $cliente['nombre'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['ci'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['celular'] }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold">{{ $saldo }}</span> pts
                            </td>
                            <td class="px-6 py-4">
                                @if($puedeCanjer)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Puede canjear Bs 10
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Necesita {{ 100 - $saldo }} pts más
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Productos --}}
    @if(!is_null($cliente))
        <flux:separator />
        <div class="flex-1 max-md:py-6 self-stretch my-4">
            <flux:heading size="xl" level="1">Productos</flux:heading>
            <flux:text class="mb-6 mt-2 text-base">
                Ingresa los productos para continuar con la venta.
            </flux:text>
        </div>
        <livewire:venta-detalle :productos="$productos" />
    @endif

</x-layouts.app>