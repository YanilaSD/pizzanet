<x-layouts.app>
    <div class="flex justify-between">
        <div class="">
            <h1 class="text-2xl">Gestión de Categorias</h1>
            <p>Registra y gestiona todos los categorias del sistema.</p>
        </div>
        <flux:button variant="primary" color="orange" href="{{ route('categorias.create') }}">
            Crear categoria
        </flux:button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <table class="w-full bg-white text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                <div class="flex justify-between items-center">
                    <div>
                        Categoria
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400 mb-2.5">Listado de los categorias registrados</p>
                    </div>
                </div>
            </caption>

            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categorias as $categoria)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->index + 1 }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $categoria->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge color="{{ $categoria->estado == 1 ? 'green' : 'red' }}">
                                {{ $categoria->estado == '1' ? 'Activo' : 'Inactivo' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-2.5">
                            <a href="{{ route('categorias.edit', $categoria) }}" class="dark:text-white text-gray-500 hover:underline mr-4">
                                <flux:icon name="pencil-square" />
                            </a>
                            @if($categoria->estado == '1')
                                <a href="{{ route('categorias.destroy', $categoria) }}" class="dark:text-white text-gray-500 hover:underline mr-4">
                                    <flux:icon name="trash" />
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center dark:text-gray-500 text-gray-400">
                            No hay tipos de pago registrados en el sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-white">
            {{ $categorias->links() }}
        </div>
    </div>

</x-layouts.app>
