<x-layouts.app>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="font-semibold">Revisa los campos:</div>
            <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-module-card-lg title="Nuevo Producto" description="Completa el formulario para registrar un nuevo producto.">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nombre" class="module-form-label">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Ej: Pizza Familiar"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                <div>
                    <label for="precio" class="module-form-label">
                        Precio <span class="text-red-500">*</span>
                    </label>
                    <flux:input
                        id="precio"
                        name="precio"
                        placeholder="Ej: 59.90"
                        value="{{ old('precio') }}"
                        type="number"
                        inputmode="decimal"
                        min="0"
                        step="0.01"
                        required
                    />
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Usa punto para decimales. Ej: 59.90</p>
                </div>

                <div>
                    <label for="categoria_id" class="module-form-label">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="categoria_id"
                        name="categoria_id"
                        class="block w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        required
                    >
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="descripcion" class="module-form-label">
                        Descripción <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="3"
                        placeholder="Ej: Pizza con mozzarella, jamón y champiñones..."
                        class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        required
                    >{{ old('descripcion') }}</textarea>
                </div>

                <div>
                    <label for="imagen" class="module-form-label">
                        Imagen <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="imagen"
                        name="imagen"
                        type="file"
                        accept="image/*"
                        class="block w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 text-sm
                               file:mr-3 file:border-0 file:bg-orange-500 file:text-white file:px-3 file:py-2 file:rounded-md
                               hover:file:bg-orange-600
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        required
                    />
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Formato sugerido: JPG/PNG. Máx recomendado: 10MB.</p>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('productos.index') }}">
                    <flux:button
                        variant="primary"
                        color="gray"
                >
                        Volver
                    </flux:button>
                </a>

                <flux:button
                    type="submit"
                    variant="primary"
                    color="orange"
                >
                    Guardar
                </flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>