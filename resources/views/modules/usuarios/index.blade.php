<x-layouts.app>
    <x-card-header 
        title="Gestión de Usuarios"
        description="Registra y gestiona todos los usuarios del sistema"
        button-text="Nuevo usuario"
        :button-link="route('usuarios.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table 
        title="Registro de Usuarios"
        description="Listado de usuarios registrados"
    >
        <x-slot name="actions">
            <x-module-search-form
                :action="route('usuarios.index')"
                placeholder="Buscar usuario"
                :value="request('search')"
            />
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
                <tr class="module-table-tr">
                    <td class="module-table-td-index">
                        {{ $loop->iteration }}
                    </td>

                    <td class="module-table-td-primary">
                        {{ $usuario->name }}
                    </td>

                    <td class="module-table-td">
                        {{ $usuario->email }}
                    </td>

                    <td class="px-6 py-4">
                        @if($usuario->roles->isEmpty())
                            <span class="module-muted">Sin roles</span>
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

                    <td class="module-table-td-actions">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}"
                           class="module-icon-btn-blue">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('usuarios.reset', $usuario->id) }}"
                        data-confirm="Se generará una nueva contraseña para este usuario."
                        data-confirm-title="Restablecer contraseña"
                        data-confirm-variant="warning"
                        data-confirm-confirm="Sí, restablecer"
                        class="module-icon-btn-yellow">
                            <flux:icon name="shield-exclamation" />
                        </a>

                        <a href="{{ route('usuarios.toggle', $usuario->id) }}"
                        data-confirm="¿Desea {{ $usuario->estado == '1' ? 'desactivar' : 'activar' }} este usuario?"
                        data-confirm-title="{{ $usuario->estado == '1' ? 'Desactivar usuario' : 'Activar usuario' }}"
                        data-confirm-variant="question"
                        data-confirm-confirm="Sí, {{ $usuario->estado == '1' ? 'desactivar' : 'activar' }}"
                        class="{{ $usuario->estado == '1' ? 'module-icon-btn-red' : 'module-icon-btn-green' }}">
                            <flux:icon name="{{ $usuario->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                        </a>

                        @if($usuario->two_factor_secret != null)
                            <form method="POST" action="{{ route('usuarios.reset_f2a', $usuario->id) }}"
                                data-confirm="Se desactivará la autenticación de dos factores para este usuario."
                                data-confirm-title="Restablecer 2FA"
                                data-confirm-variant="warning"
                                data-confirm-confirm="Sí, restablecer 2FA">
                                @csrf
                                <button type="submit" class="module-icon-btn-purple cursor-pointer">
                                    <flux:icon name="qr-code" />
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="module-table-empty">
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