<x-layouts.app>
    <x-card-header 
        title="Gestión de Productos"
        description="Registra y gestiona todos los productos del sistema."
        button-text="Nuevo producto"
        :button-link="route('productos.create')"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="my-4 gap-4 space-y-2">
            @foreach ($errors->all() as $error)
                <flux:callout variant="danger" icon="x-circle" heading="{{ $error }}" />
            @endforeach
        </div>
    @endif

    <div x-data="{
        productoId: null,
        productoNombre: '',
        cantidad: 0,
        abrirInventario(id, nombre, stock) {
            this.productoId = id;
            this.productoNombre = nombre;
            this.cantidad = stock;
            this.$flux.modal('inventario').show();
        }
    }">
        <x-data-table 
            title="Registro de Productos"
            description="Listado de productos registrados"
        >
            <x-slot name="actions">
                <form method="GET" action="{{ route('productos.index') }}">
                    <flux:input name="search" icon="magnifying-glass" placeholder="Buscar producto" value="{{ request('search') }}" />
                </form>
            </x-slot>

            <x-slot name="head">
                <th>#</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Inventario</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </x-slot>

            <x-slot name="body">
                @forelse ($productos as $producto)
                    <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                        <td class="px-6 py-4 text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen de {{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-md">
                            @else
                                <span class="text-gray-400">No disponible</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                            {{ $producto->nombre }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                            {{ $producto->categoria->nombre }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                            Bs {{ number_format($producto->precio, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition">
                            {{ $producto->inventario?->cantidad ?? 0 }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $producto->estado == 1 ? 'green' : 'red' }}">
                                {{ $producto->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <button
                                type="button"
                                class="p-2 rounded-lg hover:bg-orange-100 text-gray-500 hover:text-orange-600 transition"
                                x-on:click="abrirInventario({{ $producto->id }}, @js($producto->nombre), {{ $producto->inventario?->cantidad ?? 0 }})"
                            >
                                <flux:icon name="clipboard-document-list" />
                            </button>

                            <a href="{{ route('productos.edit', $producto->id) }}"
                               class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                                <flux:icon name="pencil-square" />
                            </a>

                            <a href="{{ route('productos.toggle', $producto) }}"
                               class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                                <flux:icon name="{{ $producto->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">
                            No se encontraron productos registrados.
                        </td>
                    </tr>
                @endforelse
            </x-slot>

            <x-slot name="pagination">
                {{ $productos->links() }}
            </x-slot>
        </x-data-table>

        <flux:modal name="inventario" class="md:w-96">
            <form
                method="POST"
                x-bind:action="'{{ url('productos') }}/' + productoId + '/inventario'"
            >
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Actualizar inventario</flux:heading>
                        <flux:text class="mt-2">
                            Producto: <span class="font-medium" x-text="productoNombre"></span>
                        </flux:text>
                    </div>

                    <flux:input
                        label="Cantidad en stock"
                        name="cantidad"
                        type="number"
                        min="0"
                        x-model="cantidad"
                        placeholder="Ingresa la cantidad disponible"
                    />

                    <div class="flex gap-2">
                        <flux:modal.close>
                            <flux:button type="button">Cancelar</flux:button>
                        </flux:modal.close>
                        <flux:spacer />
                        <flux:button type="submit" variant="primary">Guardar</flux:button>
                    </div>
                </div>
            </form>
        </flux:modal>
    </div>
</x-layouts.app>
