<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Editar Cliente</h1>
            <p>Modifica los detalles del cliente.</p>
        </div>
        <a href="{{ route('clientes.index') }}">
            <flux:button color="gray">← Volver</flux:button>
        </a>
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

    {{-- Formulario de Cliente --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Necesario para indicar que es una actualización --}}

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:w-1/2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Nombre</label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del cliente"
                        value="{{ old('nombre', $cliente->nombre) }}" {{-- Prellenar con el valor actual --}}
                        required
                    />
                </div>

                {{-- CI --}}
                <div class="md:w-1/2">
                    <label for="ci" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">CI</label>
                    <flux:input
                        id="ci"
                        name="ci"
                        placeholder="CI del cliente"
                        value="{{ old('ci', $cliente->ci) }}"
                        required
                    />
                </div>

                {{-- Celular --}}
                <div class="md:w-1/2">
                    <label for="celular" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Celular</label>
                    <flux:input
                        id="celular"
                        name="celular"
                        placeholder="Celular del cliente"
                        value="{{ old('celular', $cliente->celular) }}"
                        required
                    />
                </div>

                {{-- Correo --}}
                <div class="md:w-1/2">
                    <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Correo</label>
                    <flux:input
                        id="correo"
                        name="correo"
                        type="email"
                        placeholder="Correo del cliente"
                        value="{{ old('correo', $cliente->correo) }}" {{-- Prellenar con el valor actual --}}
                        required
                    />
                </div>
            </div>

            {{-- Botón de guardar --}}
            <div class="flex justify-end">
                <flux:button type="submit">Actualizar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
