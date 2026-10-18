<x-layouts.app>

    <div class="p-6">
        <div class="mb-6">
            <h1 class="module-page-title text-2xl">
                Reporte de Productos
            </h1>
            <p class="module-body-text">
                Consulta y analiza los productos del sistema.
            </p>
        </div>

        <form method="GET"
              class="module-card-lg mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">

            <div>
                <label class="module-form-label">Estado</label>
                <flux:select name="estado" placeholder="Seleccionar estado...">
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
                <label class="module-form-label">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    placeholder="Buscar por nombre..."
                    value="{{ request('nombre') }}"
                    class="module-input-native"
                >
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end md:col-span-2">

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

            <x-module-card>
                <p class="module-card-label">Total Productos</p>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $total ?? 0 }}
                </p></x-module-card>

            <x-module-card>
                <p class="module-card-label">Activos</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ $activos ?? 0 }}
                </p></x-module-card>

            <x-module-card>
                <p class="module-card-label">Inactivos</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ $inactivos ?? 0 }}
                </p></x-module-card>

        </div>
        <x-module-card-lg class="overflow-hidden !p-0">

            <div class="module-table-scroll">
            <table class="module-report-table">
                <thead>
                    <tr>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Categoría</th>
                        <th class="p-4">Inventario</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($productos as $producto)
                        <tr class="group">
                            <td class="p-4">{{ $producto->nombre }}</td>
                            <td class="p-4">{{ $producto->categoria->nombre ?? '-' }}</td>
                            <td class="p-4">{{ $producto->inventario?->cantidad ?? 0 }}</td>
                            <td class="p-4">
                                @if($producto->estado == 1)
                                    <span class="module-status-badge-green">
                                        Activo
                                    </span>
                                @else
                                    <span class="module-status-badge-red">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="module-table-empty p-6">
                                No existen productos para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div></x-module-card-lg>
    </div>

</x-layouts.app>