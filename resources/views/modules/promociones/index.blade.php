<x-layouts.app>
    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Gestión de Promociones</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Registra y gestiona todas las promociones del sistema.
            </p>
        </div>

        <flux:button variant="primary" color="orange" href="{{ route('promociones.create') }}">
            Agregar nueva promoción
        </flux:button>
    </div>

    {{-- Success --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg my-4" role="alert">
            <div class="font-semibold">¡Éxito!</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
        <table class="w-full bg-white text-sm text-left text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        Registro de Promociones
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                            Listado de promociones registradas
                        </p>
                    </div>

                    <form method="GET" action="{{ route('promociones.index') }}" class="w-full sm:w-auto">
                        <flux:input
                            name="search"
                            icon="magnifying-glass"
                            placeholder="Buscar promoción"
                            value="{{ request('search') }}"
                        />
                    </form>
                </div>
            </caption>

            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Festividad</th>
                    <th scope="col" class="px-6 py-3">Descuento</th>
                    <th scope="col" class="px-6 py-3">Inicio</th>
                    <th scope="col" class="px-6 py-3">Fin</th>
                    <th scope="col" class="px-6 py-3">Compra mínima</th>
                    <th scope="col" class="px-6 py-3">Límite de uso</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($promociones as $promocion)
                    <tr class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $loop->iteration }}
                        </th>

                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $promocion->nombre }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $promocion->festividad->nombre }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ rtrim(rtrim(number_format($promocion->descuento, 2), '0'), '.') }}%
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            Bs {{ number_format($promocion->compra_minima, 2) }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $promocion->limite_uso }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <flux:badge color="{{ $promocion->estado == '1' ? 'green' : 'red' }}">
                                {{ $promocion->estado == '1' ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('promociones.edit', $promocion->id) }}"
                                   class="text-gray-500 dark:text-white hover:text-orange-500">
                                    <flux:icon name="pencil-square" />
                                </a>

                                <a href="{{ route('promociones.destroy', $promocion) }}"
                                   class="text-gray-500 dark:text-white hover:text-red-500">
                                    <flux:icon name="trash" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No se encontraron promociones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-white dark:bg-gray-800">
            {{ $promociones->links() }}
        </div>
    </div>
</x-layouts.app>