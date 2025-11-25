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
    <form action="{{ route('ventas.searchClient') }}" method="post">
        @csrf
        <div class="flex-1 max-md:py-6 self-stretch my-4">
            <flux:heading size="xl" level="1">Datos del cliente</flux:heading>
            <flux:text class="mb-6 mt-2 text-base">Ingresa la información del cliente para continuar con la venta.</flux:text>
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
            <div class="col-span-2">
                <label for="ci" class="block text-sm font-medium text-gray-700">Cliente</label>
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

    @if(!is_null($cliente))
        <div class="col-span-2 my-4">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">CI</th>
                            <th class="px-6 py-3">Celular</th>
                            <th class="px-6 py-3">Puntos</th>
                            <th class="px-6 py-3">Descuentos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4">{{ $cliente['id'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['nombre'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['ci'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['celular'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['puntos'] }}</td>
                            <td class="px-6 py-4">{{ $cliente['descuento'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    
    @if(!is_null($cliente))
        <flux:separator />
        <div class="flex-1 max-md:py-6 self-stretch my-4">
            <flux:heading size="xl" level="1">Productos</flux:heading>
            <flux:text class="mb-6 mt-2 text-base">Ingresa los productos para continuar con la venta.</flux:text>
        </div>
        <livewire:venta-detalle :productos="$productos" />
    @endif


</x-layouts.app>
