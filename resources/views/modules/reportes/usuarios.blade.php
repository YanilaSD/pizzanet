<x-layouts.app>

    <div class="p-6">
        <div class="mb-6">
            <h1 class="module-page-title text-2xl">
                Reporte de Usuarios
            </h1>
            <p class="module-body-text">
                Consulta y analiza los usuarios del sistema.
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

    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-end">

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

            <x-module-card>
                <p class="module-card-label">Total Usuarios</p>
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
                        <th class="p-4">#</th>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Fecha Registro</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
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
                            <td colspan="5"
                                class="module-table-empty p-6">
                                No existen usuarios para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
            </div>
</x-module-card-lg>

    </div>

</x-layouts.app>
