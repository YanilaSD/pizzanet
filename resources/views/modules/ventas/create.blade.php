<x-layouts.app>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-4">

        <div class="lg:col-span-3">
            <div class="flex flex-col">
                <h1 class="module-page-title sm:text-2xl">
                    Gestión de Ventas
                </h1>

                <p class="module-body-text mt-1 text-sm sm:text-base">
                    Registra y gestiona todas las ventas de la pizzería con promociones
                </p>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="module-card-nested text-sm dark:text-white">
                <p>
                    <span class="font-medium">Usuario:</span>
                    {{ auth()->user()->name }}
                </p>

                <p class="mt-1">
                    <span class="font-medium">Fecha:</span>
                    {{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('d/m/Y') }}
                </p>
            </div>
        </div>

    </div>

    <flux:separator />

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

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 my-4">
            <div class="lg:col-span-3">
                <label for="ci" class="module-form-label">Cliente</label>
                <flux:input.group class="flex flex-col sm:flex-row gap-2">
                <flux:input
                    name="ci"
                    placeholder="Ingresa CI del cliente"
                />

                <flux:button
                    type="submit"
                    name="action"
                    value="buscar"
                    icon="magnifying-glass"
                >
                    Buscar
                </flux:button>

                <flux:button
                    type="submit"
                    name="action"
                    value="sin_cliente"
                    variant="primary"
                    color="orange"
                    icon="shopping-cart"
                >
                    Venta sin cliente
                </flux:button>
            </flux:input.group>
            </div>
        </div>
    </form>

    @if(!is_null($cliente))
        <div class="my-4">
           <x-module-card-lg class="!p-0">
                <div class="overflow-x-auto">
                    <table class="module-report-table min-w-[800px]">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>CI</th>
                                <th>Celular</th>
                                <th>Puntos acumulados</th>
                                <th>Estado canje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $clienteModel = \App\Models\Cliente::find($cliente['id']);
                                $saldo = (int) ($clienteModel?->saldo_puntos ?? 0);
                                $canje = \App\Models\CanjePunto::where('estado', 1)->first();
                                $disponible = $canje?->canjeDisponible($saldo) ?? ['bloques' => 0, 'puntos' => 0, 'descuento' => 0];
                                $puntosRequeridos = (int) ($canje?->puntos ?? 0);
                                $puedeCanjer = ! $clienteModel?->esAnonimo() && $disponible['bloques'] >= 1;
                            @endphp
                            <tr class="module-table-tr">
                                <td class="module-table-td-primary !px-4">{{ $cliente['nombre'] }}</td>
                                <td class="module-table-td !px-4">{{ $cliente['ci'] }}</td>
                                <td class="module-table-td !px-4">{{ $cliente['celular'] }}</td>
                                <td class="module-table-td !px-4">
                                    <span class="font-semibold">{{ $saldo }}</span> pts
                                </td>
                                <td class="module-table-td !px-4">
                                    @if($clienteModel?->esAnonimo())
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                        No aplica
                                    </span>
                                    @else
                                        @if($puedeCanjer)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Puede canjear Bs {{ number_format($disponible['descuento'], 2) }} ({{ $disponible['puntos'] }} pts)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                                Necesita {{ max($puntosRequeridos - $saldo, 0) }} pts más
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-module-card-lg>
        </div>
    @endif

    @if(!is_null($cliente))
        <flux:separator />
        <div class="my-4">
            <flux:heading size="xl" level="1">
                Productos
            </flux:heading>

            <flux:text class="mt-2 mb-6 text-sm sm:text-base">
                Ingresa los productos para continuar con la venta.
            </flux:text>
        </div>
        <livewire:venta-detalle :productos="$productos" />
    @endif

</x-layouts.app>