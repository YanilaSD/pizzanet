<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Gestión de Promociones</h1>
            <p>Registra y gestiona todas las promociones del sistema.</p>
        </div>
        <flux:button href="{{ route('promociones.create') }}">
            + Nueva Promoción
        </flux:button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-2.5">
            <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                <div class="row justify-between flex items-center">
                    <div>
                        Registro de Promociones
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400 mb-2.5">Listado de promociones registradas</p>
                    </div>
                    <form method="GET" action="{{ route('promociones.index') }}" class="flex">
                        <flux:input name="search" icon="magnifying-glass" placeholder="Buscar promoción"
                            value="{{ request('search') }}" />
                    </form>
                </div>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Festividad</th>
                    <th scope="col" class="px-6 py-3">Descuento</th>
                    <th scope="col" class="px-6 py-3">Fecha Inicio</th>
                    <th scope="col" class="px-6 py-3">Fecha Fin</th>
                    <th scope="col" class="px-6 py-3">Compra Mínima</th>
                    <th scope="col" class="px-6 py-3">Límite de Uso</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($promociones as $promocion)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </th>
                        <td class="px-6 py-4">{{ $promocion->nombre }}</td>
                        <td class="px-6 py-4">{{ $promocion->festividad->nombre }}</td>
                        <td class="px-6 py-4">{{ $promocion->descuento }}%</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $promocion->compra_minima }}</td>
                        <td class="px-6 py-4">{{ $promocion->limite_uso }}</td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $promocion->estado == '1' ? 'green' : 'red' }}">
                                {{ $promocion->estado == '1' ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-2.5">
                            <a href="{{ route('promociones.show', $promocion->id) }}" class="text-white dark:text-gray-500 hover:underline mr-4">
                                <flux:icon name="eye" />
                            </a>

                            <a href="{{ route('promociones.edit', $promocion->id) }}"
                                class="text-white dark:text-gray-500 hover:underline mr-4">
                                <flux:icon name="pencil-square" />
                            </a>

                            <a href="{{ route('promociones.destroy', $promocion) }}"
                                class="text-white dark:text-gray-500 hover:underline mr-4">
                                <flux:icon name="trash" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No se encontraron promociones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $promociones->links() }}
    </div>
</x-layouts.app>
