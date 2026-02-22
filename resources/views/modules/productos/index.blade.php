<x-layouts.app>
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl">Gestión de Productos</h1>
            <p>Registra y gestiona todos los productos del sistema</p>
        </div>
        <flux:button variant="primary" color="orange" href="{{ route('productos.create') }}">
            Nuevo Producto
        </flux:button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <table class="w-full bg-white text-sm text-left text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                <div class="row justify-between flex items-center">
                    <div>
                        Registro de Productos
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400 mb-2.5">Listado de productos registrados</p>
                    </div>
                    <form method="GET" action="{{ route('productos.index') }}" class="flex">
                        <flux:input name="search" icon="magnifying-glass" placeholder="Buscar producto"
                            value="{{ request('search') }}" />
                    </form>
                </div>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Imagen</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Categoría</th>
                    <th scope="col" class="px-6 py-3">Precio</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </th>
                        <td class="px-6 py-4">
                            @if($producto->imagen)
                                <img src="{{ $producto->imagenUrl }}" alt="Imagen de {{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-md">
                            @else
                                <span>No disponible</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4">{{ $producto->categoria->nombre }}</td>
                        <td class="px-6 py-4">Bs {{ number_format($producto->precio, 2) }}</td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $producto->estado == 1 ? 'green' : 'red' }}">
                                {{ $producto->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <a href="{{ route('productos.edit', $producto->id) }}"
                                class="text-gray-500 dark:text-white hover:text-orange-500">
                                    <flux:icon name="pencil-square" />
                                </a>

                                <a href="{{ route('productos.toggle', $producto) }}"
                                class="text-gray-500 dark:text-white hover:text-red-500">
                                    <flux:icon name="{{ $producto->estado == '0' ? 'check-circle' : 'no-symbol' }}" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No se encontraron productos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-white">
            {{ $productos->links() }}
        </div>
    </div>
</x-layouts.app>
