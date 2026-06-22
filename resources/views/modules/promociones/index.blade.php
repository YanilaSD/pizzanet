<x-layouts.app>
    <x-card-header 
        title="Gestión de Promociones"
        description="Registra y gestiona todas las promociones del sistema."
        button-text="Nueva promoción"
        :button-link="route('promociones.create')"
    />

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg my-4" role="alert">
            <div class="font-semibold">¡Éxito!</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <x-data-table 
        title="Registro de Promociones"
        description="Listado de promociones registradas"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('promociones.index') }}">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Buscar promoción"
                    value="{{ request('search') }}"
                />
            </form>
        </x-slot>

        <x-slot name="head">
            <th>#</th>
            <th>Nombre</th>
            <th>Festividad</th>
            <th>Descuento</th>
            <th>Vigencia</th>
            <th>Compra mínima</th>
            <th>Límite de uso</th>
            <th>Estado</th>
            <th class="text-right">Acciones</th>
        </x-slot>

        <x-slot name="body">
            @forelse ($promociones as $promocion)
                <tr class="group hover:bg-orange-50/40 transition-all duration-200">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800 group-hover:text-gray-900 transition">
                        {{ $promocion->nombre }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                        {{ $promocion->festividad->nombre }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                        {{ rtrim(rtrim(number_format($promocion->descuento, 2), '0'), '.') }}%
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap text-xs">
                        {{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                        Bs {{ number_format($promocion->compra_minima, 2) }}
                    </td>

                    <td class="px-6 py-4 text-gray-600 group-hover:text-gray-800 transition whitespace-nowrap">
                        {{ $promocion->limite_uso }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="{{ $promocion->estado == '1' ? 'green' : 'red' }}">
                            {{ $promocion->estado == '1' ? 'Activo' : 'Inactivo' }}
                        </flux:badge>
                    </td>

                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('promociones.edit', $promocion->id) }}"
                           class="p-2 rounded-lg hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition">
                            <flux:icon name="pencil-square" />
                        </a>

                        <a href="{{ route('promociones.destroy', $promocion) }}"
                           class="p-2 rounded-lg hover:bg-red-100 text-gray-500 hover:text-red-600 transition">
                            <flux:icon name="trash" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-8 text-gray-400">
                        No se encontraron promociones registradas.
                    </td>
                </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $promociones->links() }}
        </x-slot>
    </x-data-table>
</x-layouts.app>