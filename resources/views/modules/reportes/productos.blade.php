<x-layouts.app>

    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Reporte de Productos
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Consulta y analiza los productos del sistema.
            </p>
        </div>

        <form method="GET"
            class="bg-white rounded-2xl shadow p-6 grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <div>
                <label class="text-sm text-gray-600">Estado</label>
                <flux:select name="estado" class="dark" placeholder="Seleccionar estado...">
                    <flux:select.option value="">Todos</flux:select.option>
                    <flux:select.option value="1" :selected="request('estado') === '1'">
                        Activo
                    </flux:select.option>
                    <flux:select.option value="0" :selected="request('estado') === '0'">
                        Inactivo
                    </flux:select.option>
                </flux:select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    placeholder="Buscar por nombre..."
                    value="{{ request('nombre') }}"
                    class="w-full border placeholder:text-gray-400 border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
            </div>

            <div>
                <label class="text-sm text-gray-600">Precio mínimo</label>
                <input
                    type="number"
                    name="precio_min"
                    placeholder="0.00"
                    value="{{ request('precio_min') }}"
                    class="w-full border placeholder:text-gray-400 border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
            </div>

            <div>
                <label class="text-sm text-gray-600">Precio máximo</label>
                <input
                    type="number"
                    name="precio_max"
                    placeholder="0.00"
                    value="{{ request('precio_max') }}"
                    class="w-full border placeholder:text-gray-400 border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
            </div>

            <div class="md:col-span-4 flex justify-end gap-2">

                <button
                    type="submit"
                    class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition cursor-pointer"
                >
                    Filtrar
                </button>

                <a
                    href="{{ route('reportes.productos.pdf', request()->query()) }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    PDF
                </a>

            </div>

        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Total Productos</p>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $total ?? 0 }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Activos</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ $activos ?? 0 }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Inactivos</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ $inactivos ?? 0 }}
                </p>
            </div>

        </div>
        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Categoría</th>
                        <th class="p-4">Precio</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($productos as $producto)
                        <tr class="border-t hover:bg-gray-50 dark:text-gray-400 group">
                            <td class="p-4">{{ $producto->nombre }}</td>
                            <td class="p-4">{{ $producto->categoria->nombre ?? '-' }}</td>
                            <td class="p-4">Bs {{ number_format($producto->precio, 2) }}</td>
                            <td class="p-4">
                                @if($producto->estado == 1)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-lg">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-lg">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="p-6 text-center text-gray-500">
                                No existen productos para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>