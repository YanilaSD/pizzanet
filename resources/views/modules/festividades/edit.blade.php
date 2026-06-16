<x-layouts.app>
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

    <x-module-card-lg title="Editar Festividad" description="Completa el formulario para actualizar la festividad.">
        <form action="{{ route('festividades.update', $festividad->id) }}" class="space-y-3" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-1">
                    <label for="nombre" class="module-form-label">Nombre <span class="text-red-500">*</span></label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre de la festividad"
                        value="{{ old('nombre', $festividad->nombre) }}"
                        required
                    />
                </div>

                <div class="md:col-span-1">
                    <label for="descripcion" class="module-form-label">Descripción <span class="text-red-500">*</span></label>
                    <flux:input
                        id="descripcion"
                        name="descripcion"
                        placeholder="Descripción de la festividad"
                        value="{{ old('descripcion', $festividad->descripcion) }}"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('festividades.index') }}">
                    <flux:button variant="primary" color="gray">Volver</flux:button>
                </a>
                <flux:button variant="primary" color="orange" type="submit">Actualizar</flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>
