<x-layouts.app>
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl">Gestión de Festividades</h1>
            <p>Registra y gestiona todas las festividades del sistema</p>
        </div>
        <flux:button href="{{ route('festividades.create') }}">
            + Nueva Festividad
        </flux:button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2.5">
            <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                <div class="row justify-between flex items-center">
                    <div>
                        Registro de Festividades
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400 mb-2.5">Listado de festividades registradas</p>
                    </div>
                    <!-- Buscador -->
                    <form method="GET" action="{{ route('festividades.index') }}" class="flex items-center gap-2">
                        <flux:input name="search" icon="magnifying-glass" placeholder="Buscar festividad" value="{{ request('search') }}" />
                    </form>
                </div>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Descripción</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($festividades as $festividad)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </th>
                        <td class="px-6 py-4">{{ $festividad->nombre }}</td>
                        <td class="px-6 py-4">{{ $festividad->descripcion }}</td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $festividad->estado == '1' ? 'green' : 'red' }}">
                                {{ $festividad->estado == '1' ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-2.5">

                            <a href="{{ route('festividades.edit', $festividad) }}" class="text-white dark:text-gray-500 hover:underline mr-4">
                                <flux:icon name="pencil-square" />
                            </a>
                            <a href="{{ route('festividades.destroy', $festividad->id) }}" class="text-white dark:text-gray-500 hover:underline mr-4">
                                <flux:icon name="trash" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No se encontraron festividades registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $festividades->links() }}
    </div>
</x-layouts.app>
