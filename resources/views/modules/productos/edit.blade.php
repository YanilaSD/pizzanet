<x-layouts.app>
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Editar Producto</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Modifica los detalles del producto.
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
        <form
            action="{{ route('productos.update', $producto->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Nombre --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Nombre
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
                    <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Precio
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
                    <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Categoría
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
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                        Descripción
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
                    <label for="imagen" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
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
    </div>
</x-layouts.app>