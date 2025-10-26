<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Editar Producto</h1>
            <p>Modifica los detalles del producto.</p>
        </div>
        <a href="{{ route('productos.index') }}">
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
        <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:w-1/3">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Nombre</label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del producto"
                        value="{{ old('nombre', $producto->nombre) }}"
                        required
                    />
                </div>

                {{-- Descripción --}}
                <div class="md:w-2/3">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Descripción</label>
                    <flux:input
                        id="descripcion"
                        name="descripcion"
                        placeholder="Descripción del producto"
                        value="{{ old('descripcion', $producto->descripcion) }}"
                    />
                </div>
            </div>

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Precio --}}
                <div class="md:w-1/3">
                    <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Precio</label>
                    <flux:input
                        id="precio"
                        name="precio"
                        type="number"
                        step="0.01"
                        value="{{ old('precio', $producto->precio) }}"
                        required
                    />
                </div>


            {{-- Selección de Categoría --}}
            <div class="md:w-1/3">
                <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Categoría</label>
                <select
                    id="categoria_id"
                    name="categoria_id"
                    class="block w-full mt-1 border h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
                >
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

                {{-- Imagen --}}
                <div class="md:w-1/3">
                    <label for="imagen" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Imagen</label>
                    <input
                        id="imagen"
                        name="imagen"
                        type="file"
                        accept="image/*"
                        class="block w-full mt-1 border h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    {{-- Si ya existe una imagen, la mostramos --}}
                    @if ($producto->imagen)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto" class="max-w-xs">
                        </div>
                    @endif
                </div>
            </div>


            {{-- Botón de guardar --}}
            <div class="flex justify-end">
                <flux:button type="submit">Guardar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
