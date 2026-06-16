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

    <x-module-card-lg title="Editar Categoria" description="Modifica los datos de la categoria en el sistema.">
        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                <div class="md:flex-[2]">
                    <label for="nombre" class="module-form-label">Nombre <span class="text-red-500">*</span></label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre de la categoria"
                        value="{{ old('nombre', $categoria->nombre) }}"
                        required
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('categorias.index') }}">
                    <flux:button variant="primary" color="gray">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" color="orange" type="submit">Actualizar</flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>
