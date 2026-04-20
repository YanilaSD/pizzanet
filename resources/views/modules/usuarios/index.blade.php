<x-layouts.app>
    <x-card-header 
        title="Gestión de Usuarios"
        description="Registra y gestiona todos los usuarios del sistema"
        button-text="Nuevo usuario"
        :button-link="route('usuarios.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Registro de Usuarios"
        description="Listado de usuarios registrados"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('usuarios.index') }}">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Buscar usuario"
                    value="{{ request('search') }}"
                />
            </form>
        </x-slot>

        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($usuarios as $usuario)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $usuario->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                        {{ $usuario->email }}
                    </td>

                    <td class="px-6 py-4">
                        @if($usuario->roles->isEmpty())
                            <span class="text-gray-400">Sin roles</span>
                        @else
                            <div class="flex flex-wrap gap-1">
                                @foreach ($usuario->roles as $rol)
                                    <flux:badge color="gray">
                                        {{ $rol->nombre }}
                                    </flux:badge>
                                @endforeach
                            </div>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <flux:badge color="{{ $usuario->estado == '1' ? 'green' : 'red' }}">
                            {{ $usuario->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('usuarios.reset', $usuario->id) }}"
                           class="p-2 rounded-lg hover:bg-yellow-100 text-gray-500 hover:text-yellow-600 transition">
                            <flux:icon name="shield-exclamation" />
                        </a>

                        <a href="{{ route('usuarios.toggle', $usuario->id) }}"
                           class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                            <flux:icon name="{{ $usuario->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>

                        @if($usuario->two_factor_secret != null)
                            <form method="POST" action="{{ route('usuarios.reset_f2a', $usuario->id) }}">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg hover:bg-purple-100 text-gray-500 hover:text-purple-600 transition cursor-pointer">
                                    <flux:icon name="qr-code" />
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-400">
                        No se encontraron usuarios registrados.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $usuarios->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>