<x-layouts.app>
    <x-card-header 
        title="Gestión de Clientes"
        description="Registra y gestiona todos los clientes del sistema."
        button-text="Nuevo cliente"
        :button-link="route('clientes.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
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
                    <tr class="module-table-tr">
                        <td class="module-table-td-index">
                            {{ $loop->iteration }}
                        </td>

                        <td class="module-table-td-primary">
                            {{ $cliente->nombre ?? 'Sin nombre' }}
                        </td>

                         <td class="module-table-td">
                            {{ $cliente->ci ?? '-' }}
                        </td>

                         <td class="module-table-td">
                            {{ $cliente->correo ?? 'Sin correo' }}
                        </td>

                        <td class="module-table-td">
                            {{ $cliente->getSaldoPuntosAttribute() }} Pts.
                        </td>

                        <td class="px-6 py-4">
                            <flux:badge color="{{ $cliente->estado == 1 ? 'green' : 'red' }}">
                                {{ $cliente->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>

                        <td class="module-table-td-actions">
                            @if ($cliente->ci == config("app.ci_anonimo"))
                                <span class="module-muted">No disponible</span>
                            @else
                                <a href="{{ route('clientes.canjes', $cliente) }}"
                                    class="module-icon-btn-green"
                                    title="Ver historial de canjes"
                                    aria-label="Ver historial de canjes">
                                    <flux:icon name="document-text" />
                                </a>

                                <a href="{{ route('clientes.edit', $cliente->id) }}"
                                   class="module-icon-btn-blue">
                                    <flux:icon name="pencil-square" />
                                </a>

                                <a href="{{ route('clientes.destroy', $cliente) }}"
                                   data-confirm=""
                                   data-confirm-title="¿Eliminar al cliente {{ $cliente->nombre }}?"
                                   data-confirm-variant="danger"
                                   class="module-icon-btn-red">
                                    <flux:icon name="trash" />
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="module-table-empty">
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
