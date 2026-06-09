<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Crear Nuevo Rol</h1>
            <p>Registra un nuevo rol para el sistema.</p>
        </div>
    </div>

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

    <x-module-card-lg>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                <div class="md:flex-[1]">
                    <label for="nombre" class="module-form-label">
                        Nombre
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del rol"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                <div class="md:flex-[1]">
                    <label for="descripcion" class="module-form-label">
                        Descripción
                    </label>
                    <flux:input
                        id="descripcion"
                        name="descripcion"
                        placeholder="Descripción breve"
                        value="{{ old('descripcion') }}"
                    />
                </div>
            </div>

            <div class="mb-6">
                <label class="module-form-label mb-2">
                    Privilegios
                </label>
                <div
                    class="module-card-nested grid max-h-48 grid-cols-1 gap-2 overflow-auto md:grid-cols-2">
                    @foreach ($privilegiosOptions as $option)
                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="privilegios[]"
                                value="{{ $option['value'] }}"
                                class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                {{ in_array($option['value'], old('privilegios', [])) ? 'checked' : '' }}
                            />
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $option['label'] }}
                            </span>
                        </label>
                    @endforeach
                </div>

                @error('privilegios')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-2">
                <a href="{{ route('roles.index') }}">
                    <flux:button variant="primary" color="gray">
                        Cancelar
                    </flux:button>
                </a>

                <flux:button variant="primary" color="orange" type="submit">
                    Guardar
                </flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>