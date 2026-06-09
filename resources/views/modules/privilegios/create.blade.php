<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Nuevo Privilegio</h1>
            <p>Completa el formulario para registrar un nuevo privilegio en el sistema.</p>
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

    {{-- Tarjeta del formulario --}}
    <x-module-card-lg>
        <form action="{{ route('privilegios.store') }}" method="POST">
            @csrf

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:flex-[1]">
                    <label for="nombre" class="module-form-label">Nombre</label>
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
                    <label for="descripcion" class="module-form-label">Descripción</label>
                    <flux:input
                        id="descripcion"
                        name="descripcion"
                        placeholder="Descripción breve"
                        value="{{ old('descripcion') }}"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2">
                 <a href="{{ route('privilegios.index') }}">
                    <flux:button variant="primary" color="gray">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" color="orange" type="submit">Guardar</flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>
