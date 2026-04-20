<x-layouts.app>
    <x-card-header 
        title="Gestión de Clientes"
        description="Registra y gestiona todos los clientes del sistema."
        button-text="Nuevo cliente"
        :button-link="route('clientes.create')"
    />

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

    <div x-data="{ selectedCliente: null }" class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <table class="w-full bg-white text-sm text-left text-gray-500 dark:text-gray-400">
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
                        <td class="px-6 py-4">{{ $cliente->nombre ?? 'Sin nombre' }}</td>
                        <td class="px-6 py-4">{{ $cliente->correo ?? 'Sin correo' }}</td>
                        <td class="px-6 py-4">Bs {{ $cliente->getSaldoPuntosAttribute() }}</td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $cliente->estado == 1 ? 'green' : 'red' }}">
                                {{ $cliente->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-2.5">
                            @if ($cliente->ci == config("app.ci_anonimo"))
                                No disponible
                            @else
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="dark:text-white text-gray-500 hover:underline mr-4">
                                    <flux:icon name="pencil-square" />
                                </a>

                                <a href="{{ route('clientes.destroy', $cliente) }}" class="dark:text-white text-gray-500 hover:underline mr-4">
                                    <flux:icon name="trash" />
                                </a>
                            @endif
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

        <div class="p-4 bg-white">
            {{ $clientes->links() }}
        </div>
    </div>
</x-layouts.app>
