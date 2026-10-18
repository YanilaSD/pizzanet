<x-layouts.app>
    <x-card-header 
        title="Gestión de Productos"
        description="Registra y gestiona todos los productos del sistema."
        button-text="Nuevo producto"
        :button-link="route('productos.create')"
    />

    @if (session('success'))
        <div class="module-alert-success" role="alert">
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
                    <tr class="module-table-tr">
                        <td class="module-table-td-index">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen de {{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-md">
                            @else
                                <span class="module-muted">No disponible</span>
                            @endif
                        </td>
                        <td class="module-table-td-primary">
                            {{ $producto->nombre }}
                        </td>
                        <td class="module-table-td">
                            {{ $producto->categoria->nombre }}
                        </td>
                        <td class="module-table-td">
                            Bs {{ number_format($producto->precio, 2) }}
                        </td>
                        <td class="module-table-td">
                            {{ $producto->inventario?->cantidad ?? 0 }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $producto->inventario?->cantidad > 0 ? 'green' : 'red' }}">
                                {{ $producto->inventario?->cantidad > '0' ? 'Disponible' : 'Agotado' }}
                            </flux:badge>
                        </td>
                        <td class="module-table-td-actions">
                            <button
                                type="button"
                                class="module-icon-btn-orange"
                                x-on:click="abrirInventario({{ $producto->id }}, @js($producto->nombre), {{ $producto->inventario?->cantidad ?? 0 }})"
                            >
                                <flux:icon name="clipboard-document-list" />
                            </button>

                            <a href="{{ route('productos.edit', $producto->id) }}"
                               class="module-icon-btn-blue">
                                <flux:icon name="pencil-square" />
                            </a>

                             <a href="{{ route('productos.destroy', $producto) }}"
                                   class="module-icon-btn-red">
                                    <flux:icon name="trash" />
                                </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="module-table-empty">
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
