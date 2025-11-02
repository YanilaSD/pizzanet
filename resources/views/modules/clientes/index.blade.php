<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Gestión de Clientes</h1>
            <p>Registra y gestiona todos los clientes del sistema</p>
        </div>
        <flux:button href="{{ route('clientes.create') }}">+ Nuevo Cliente</flux:button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="my-4 gap-4 space-y-2">
            @foreach ($errors->all() as $error)
                <flux:callout variant="danger" icon="x-circle" heading="{{ $error }}" />
            @endforeach
        </div>
    @endif

    <!-- <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8"> -->
    <div x-data="{ selectedCliente: null }" class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2.5">
            <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                <div class="row justify-between flex items-center">
                    <div>
                        Registro de Clientes
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400 mb-2.5">Listado de clientes registrados</p>
                    </div>
                    <form method="GET" action="{{ route('clientes.index') }}" class="flex">
                        <flux:input name="search" icon="magnifying-glass" placeholder="Buscar cliente" value="{{ request('search') }}" />
                    </form>
                </div>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Correo</th>
                    <th scope="col" class="px-6 py-3">Puntos</th>
                    <th scope="col" class="px-6 py-3">Descuento</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </th>
                        <td class="px-6 py-4">{{ $cliente->nombre }}</td>
                        <td class="px-6 py-4">{{ $cliente->correo }}</td>
                        <td class="px-6 py-4"><flux:icon name="star" color="orange" class="inline-block" variant="solid" />{{ $cliente->puntos }}</td>
                        <td class="px-6 py-4">{{ $cliente->descuento }}%</td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $cliente->estado == 1 ? 'green' : 'red' }}">
                                {{ $cliente->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-2.5">
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="dark:text-white text-gray-500 hover:underline mr-4">
                                <flux:icon name="pencil-square" />
                            </a>
                            <div class="mr-4">
                                <!-- <flux:modal.trigger name="update-points" class="cursor-pointer">
                                    <flux:icon name="star" />
                                </flux:modal.trigger> -->
                                <flux:modal.trigger
                                    name="update-points"
                                    class="cursor-pointer"
                                    @click="selectedCliente = { id: {{ $cliente->id }}, nombre: '{{ $cliente->nombre }}' }"
                                >
                                    <flux:icon name="star" />
                                </flux:modal.trigger>
                            </div>

                             <a href="{{ route('clientes.destroy', $cliente) }}" class="dark:text-white text-gray-500 hover:underline mr-4">
                                <flux:icon name="trash" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No se encontraron clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <flux:modal name="update-points" class="md:w-96">
            <form method="POST" :action="'/clientes/' + selectedCliente?.id + '/canjear'">
                @csrf
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Canjear puntos</flux:heading>
                        <flux:text class="mt-2">
                            Cliente: <span x-text="selectedCliente?.nombre"></span>
                        </flux:text>
                        <div class="mt-4">
                            <flux:callout color="amber" icon="information-circle" heading="Cada 20 puntos equivalen a 10% de descuento." />
                        </div>
                    </div>

                    <flux:input
                        name="puntos"
                        type="number"
                        label="Puntos a canjear"
                        placeholder="Cantidad de puntos"
                        min="20"
                        step="20"
                        required
                    />

                    <div class="flex">
                        <flux:spacer />
                        <flux:button type="submit" variant="primary">
                            Canjear puntos
                        </flux:button>
                    </div>
                </div>
            </form>
        </flux:modal>


        {{ $clientes->links() }}
    </div>
</x-layouts.app>
