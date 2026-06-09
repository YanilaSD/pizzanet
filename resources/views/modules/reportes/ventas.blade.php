<x-layouts.app>

    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Reporte Ventas
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Consulta y analiza las ventas del sistema.
            </p>
        </div>

        <form method="GET"
              class="module-card-lg mb-6 grid grid-cols-1 gap-4 md:grid-cols-5">

            <div>
                <label class="module-form-label">
                    Desde
                </label>

                <input
                    type="date"
                    name="desde"
                    value="{{ request('desde') }}"
                    class="w-full rounded-lg border border-gray-300 bg-white text-gray-700 dark:border-gray-500 px-2 py-1 dark:text-gray-500">
            </div>

            <div>
                <label class="module-form-label">Hasta</label>
                <input type="date"
                       name="hasta"
                       value="{{ request('hasta') }}"
                       class="w-full rounded-lg border border-gray-300 bg-white text-gray-700 dark:border-gray-500 px-2 py-1 dark:text-gray-500">
            </div>

            <div>
                <label class="module-form-label">Tipo de Pago</label>
                <flux:select name="tipo_pago_id" class="dark" placeholder="Seleccionar tipo de pago...">
                    <flux:select.option value="">Todos</flux:select.option>
                    @foreach($tipoPagos as $tp)
                        <flux:select.option value="{{ $tp->id }}" :selected="request('tipo_pago_id') == $tp->id">
                            {{ $tp->nombre }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div>
                <label class="module-form-label">Estado</label>
                <flux:select name="estado" class="dark" placeholder="Seleccionar estado...">
                    <flux:select.option value="">Todos</flux:select.option>
                    <flux:select.option value="1" :selected="request('estado') === '1'">
                        Completada
                    </flux:select.option>
                    <flux:select.option value="0" :selected="request('estado') === '0'">
                        Anulada
                    </flux:select.option>
                </flux:select>
            </div>

            <div class="flex items-end gap-2">

                <button 
                    type="submit"
                    class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition cursor-pointer"
                >
                    Filtrar
                </button>

                <button 
                    type="submit"
                    formaction="{{ route('reportes.ventas.pdf') }}"
                    class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2 cursor-pointer"
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
                <p class="module-card-label">Total Vendido</p>
                <p class="text-2xl font-bold text-orange-600">
                    Bs {{ number_format($total ?? 0, 2) }}
                </p></x-module-card>

            <x-module-card>
                <p class="module-card-label">Total Descuentos</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ $descuentos > 0 ? '- ' : '' }}Bs {{ number_format($descuentos ?? 0, 2) }}
                </p></x-module-card>

            <x-module-card>
                <p class="module-card-label">Cantidad de Ventas</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ isset($ventas) ? $ventas->count() : 0 }}
                </p></x-module-card>

        </div>

        <x-module-card-lg class="overflow-hidden !p-0">

            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th class="p-4">#</th>
                        <th class="p-4">Cliente</th>
                        <th class="p-4">Fecha</th>
                        <th class="p-4">Subtotal</th>
                        <th class="p-4">Descuento</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Tipo Pago</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ventas as $v)
                        <tr class="border-t hover:bg-gray-50 dark:text-gray-400">
                            <td class="p-4">#{{ $v->id }}</td>

                            <td class="p-4">
                                {{ $v->cliente->nombre ?? '-' }}
                            </td>

                            <td class="p-4">
                                {{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}
                            </td>

                            <td class="p-4">
                                Bs {{ number_format($v->subtotal, 2) }}
                            </td>

                            <td class="p-4 {{ $v->descuento > 0 ? 'text-red-600' : 'text-gray-400' }}">
                            {{ $v->descuento > 0 ? '- ' : '' }}Bs {{ number_format($v->descuento ?? 0, 2) }}
                            </td>

                            <td class="p-4 font-semibold">
                                Bs {{ number_format($v->total, 2) }}
                            </td>

                            <td class="p-4">
                                {{ $v->tipoPago->nombre ?? '-' }}
                            </td>

                            <td class="p-4">
                                @if($v->estado == 1)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-lg">
                                        Completada
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-lg">
                                        Anulada
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="p-6 text-center text-gray-500 dark:text-gray-400">
                                No existen ventas para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
</x-module-card-lg>

    </div>

</x-layouts.app>
