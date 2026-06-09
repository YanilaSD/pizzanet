<!-- resources/views/tipo_pagos/create.blade.php -->

<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Nuevo Tipo de Pago</h1>
            <p>Completa el formulario para registrar un nuevo tipo de pago.</p>
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
        <form action="{{ route('tipo_pagos.store') }}" method="POST">
            @csrf

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                <div class="md:w-1/3">
                    <label for="nombre" class="module-form-label">Nombre</label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del tipo de pago"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2">
                 <a href="{{ route('tipo_pagos.index') }}">
                    <flux:button variant="primary" color="gray">Volver</flux:button>
                </a>
                <flux:button color="orange" variant="primary" type="submit">Guardar</flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>
