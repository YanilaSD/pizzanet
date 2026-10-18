<x-layouts.app>
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

    <x-module-card-lg title="Nuevo Cliente" description="Completa el formulario para registrar un nuevo cliente.">
        <form action="{{ route('clientes.store') }}" class="space-y-3" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-1">
                    <label for="nombre" class="module-form-label">Nombre <span class="text-red-500">*</span></label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del cliente"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                <div class="md:col-span-1">
                    <label for="ci" class="module-form-label">CI <span class="text-red-500">*</span></label>
                    <flux:input
                        id="ci"
                        name="ci"
                        placeholder="Ej: 1234567890"
                        value="{{ old('ci') }}"
                        inputmode="numeric"
                        required
                    />
                </div>

                <div class="md:col-span-1">
                    <label for="celular" class="module-form-label">Celular <span class="text-red-500">*</span></label>
                    <flux:input
                        id="celular"
                        name="celular"
                        placeholder="Ej: 7XXXXXXX"
                        value="{{ old('celular') }}"
                        inputmode="tel"
                        required
                    />
                </div>

                <div class="md:col-span-1">
                    <label for="correo" class="module-form-label">Correo <span class="text-red-500">*</span></label>
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
    </x-module-card-lg>
</x-layouts.app>