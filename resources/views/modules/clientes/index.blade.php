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

    <div x-data="{ selectedCliente: null }">
        <x-data-table 
            title="Registro de Clientes"
            description="Listado de clientes registrados"
        >
            <x-slot name="actions">
                <form method="GET" action="{{ route('clientes.index') }}">
                    <flux:input name="search" icon="magnifying-glass" placeholder="Buscar cliente" value="{{ request('search') }}" />
                </form>
            </x-slot>

            <x-slot name="head">
                <th>#</th>
                <th>Nombre</th>
                <th>CI</th>
                <th>Correo</th>
                <th>Puntos acumulados</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </x-slot>

            <x-slot name="body">
                @forelse ($clientes as $cliente)
                    <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                        <td class="px-6 py-4 text-gray-500">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                            {{ $cliente->nombre ?? 'Sin nombre' }}
                        </td>

                         <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                            {{ $cliente->ci ?? '-' }}
                        </td>

                         <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                            {{ $cliente->correo ?? 'Sin correo' }}
                        </td>

                        <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                            {{ $cliente->getSaldoPuntosAttribute() }} Pts.
                        </td>

                        <td class="px-6 py-4">
                            <flux:badge color="{{ $cliente->estado == 1 ? 'green' : 'red' }}">
                                {{ $cliente->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>

                        <td class="px-6 py-4 flex justify-end gap-2">
                            @if ($cliente->ci == config("app.ci_anonimo"))
                                <span class="text-gray-400">No disponible</span>
                            @else
                                <a href="{{ route('clientes.canjes', $cliente) }}"
                                   class="p-2 rounded-lg hover:bg-green-100 text-gray-500 hover:text-green-600 transition">
                                    <flux:icon name="clock" />
                                </a>

                                <a href="{{ route('clientes.edit', $cliente->id) }}"
                                   class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                                    <flux:icon name="pencil-square" />
                                </a>

                                <a href="{{ route('clientes.destroy', $cliente) }}"
                                   class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                                    <flux:icon name="trash" />
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-400">
                            No se encontraron clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </x-slot>

            <x-slot name="pagination">
                {{ $clientes->links() }}
            </x-slot>
        </x-data-table>
    </div>
</x-layouts.app>
