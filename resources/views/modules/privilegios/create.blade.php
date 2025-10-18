<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Nuevo Privilegio</h1>
            <p>Completa el formulario para registrar un nuevo privilegio en el sistema.</p>
        </div>
        <a href="{{ route('privilegios.index') }}">
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

    {{-- Tarjeta del formulario --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('privilegios.store') }}" method="POST">
            @csrf

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:flex-[1]">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Nombre</label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del privilegio"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                {{-- Descripción --}}
                <div class="md:flex-[2]">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Descripción</label>
                    <flux:input
                        id="descripcion"
                        name="descripcion"
                        placeholder="Descripción breve"
                        value="{{ old('descripcion') }}"
                    />
                </div>
            </div>

            {{-- Botón alineado a la derecha --}}
            <div class="flex justify-end">
                <flux:button type="submit">Guardar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
