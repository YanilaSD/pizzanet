<x-layouts.app>

    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Reporte de Usuarios
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Consulta y analiza los usuarios del sistema.
            </p>
        </div>

       <form method="GET"
      class="bg-white rounded-2xl shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

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

    <div class="flex items-end justify-end gap-2">

        <button 
            type="submit"
            class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition cursor-pointer"
        >
            Filtrar
        </button>

        <button 
            type="submit"
            formaction="{{ route('reportes.usuarios.pdf') }}"
            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center gap-2 cursor-pointer"
        >
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v16m8-8H4" />
            </svg>
            PDF
        </button>

    </div>

</form>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Total Usuarios</p>
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
                        <th class="p-4">#</th>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Fecha Registro</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usuarios as $u)
                        <tr class="border-t hover:bg-gray-50 dark:text-gray-400">
                            <td class="p-4">#{{ $u->id }}</td>

                            <td class="p-4">
                                {{ $u->name }}
                            </td>

                            <td class="p-4">
                                {{ $u->email }}
                            </td>

                            <td class="p-4">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>

                            <td class="p-4">
                                @if($u->estado == 1)
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
                            <td colspan="5"
                                class="p-6 text-center text-gray-500">
                                No existen usuarios para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</x-layouts.app>
