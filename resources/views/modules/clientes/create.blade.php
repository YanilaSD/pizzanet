<x-layouts.app>
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Nuevo Cliente</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Completa el formulario para registrar un nuevo cliente.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <strong class="font-semibold">Revisa los campos:</strong>
            <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('clientes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Nombre
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del cliente"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                <div>
                    <label for="ci" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        CI
                    </label>
                    <flux:input
                        id="ci"
                        name="ci"
                        placeholder="CI del cliente"
                        value="{{ old('ci') }}"
                        inputmode="numeric"
                        required
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Solo números (sin espacios).
                    </p>
                </div>

                <div>
                    <label for="celular" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Celular
                    </label>
                    <flux:input
                        id="celular"
                        name="celular"
                        placeholder="Ej: 7XXXXXXX"
                        value="{{ old('celular') }}"
                        inputmode="tel"
                        required
                    />
                </div>

                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Correo
                    </label>
                    <flux:input
                        id="correo"
                        name="correo"
                        type="email"
                        placeholder="correo@ejemplo.com"
                        value="{{ old('correo') }}"
                        required
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('clientes.index') }}">
                    <flux:button variant="primary" color="gray">
                        Volver
                    </flux:button>
                </a>

                <flux:button variant="primary" color="orange" type="submit">
                    Guardar
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>