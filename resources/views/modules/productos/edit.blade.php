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

    <x-module-card-lg title="Editar Producto" description="Modifica los detalles del producto.">
        <form
            action="{{ route('productos.update', $producto->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nombre" class="module-form-label">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del producto"
                        value="{{ old('nombre', $producto->nombre) }}"
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
                        type="number"
                        step="0.01"
                        value="{{ old('precio', $producto->precio) }}"
                        required
                    />
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
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
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
                        class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Descripción del producto"
                    >{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                <div>
                    <label for="imagen" class="module-form-label">
                        Imagen
                    </label>
                    <input
                        id="imagen"
                        name="imagen"
                        type="file"
                        accept="image/*"
                        class="block w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    />

                    @if ($producto->imagen)
                        <div class="mt-3">
                            <p class="text-xs text-gray-500 mb-1">Imagen actual:</p>
                            <img
                                src="{{ asset('storage/' . $producto->imagen) }}"
                                alt="Imagen del producto"
                                class="w-24 h-24 object-cover rounded-md border"
                            >
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('productos.index') }}">
                    <flux:button variant="primary" color="gray">
                        Cancelar
                    </flux:button>
                </a>

                <flux:button type="submit" variant="primary" color="orange">
                    Guardar cambios
                </flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>