<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Editar Usuario</h1>
            <p>Actualiza la información del usuario y sus roles asignados.</p>
        </div>
    </div>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-bold">¡Ups!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Inputs --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:flex-[1]">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Nombre
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del usuario"
                        value="{{ old('nombre', $usuario->name) }}"
                        required
                    />
                </div>

                {{-- Correo --}}
                <div class="md:flex-[1]">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Correo
                    </label>
                    <flux:input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="correo@dominio.com"
                        value="{{ old('email', $usuario->email) }}"
                        required
                    />
                </div>
            </div>

            {{-- Roles --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">
                        Asignar Roles
                    </label>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Puedes seleccionar varios
                    </span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    @if ($roles->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No hay roles registrados para asignar.</p>
                    @else
                        @php
                            $selectedRoles = old('roles', $rolesAsignados ?? []);
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($roles as $rol)
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $rol->id }}"
                                        class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                        {{ in_array($rol->id, $selectedRoles) ? 'checked' : '' }}
                                    />
                                    <span>{{ $rol->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                @error('roles')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('usuarios.index') }}">
                    <flux:button variant="primary" color="gray">Cancelar</flux:button>
                </a>

                <flux:button color="orange" variant="primary" type="submit">
                    Actualizar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>