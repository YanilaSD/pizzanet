<x-layouts.app>

    <div class="p-6">

        <!-- Título -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Reporte Ventas
            </h1>
            <p class="text-gray-600">
                Consulta y analiza las ventas del sistema.
            </p>
        </div>

        <!-- Filtros -->
        <form method="GET"
              class="bg-white rounded-2xl shadow p-6 grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

            <!-- Desde -->
            <div>
                <label class="text-sm text-gray-600">Desde</label>
                <input type="date"
                       name="desde"
                       value="{{ request('desde') }}"
                       class="w-full rounded-lg border-gray-300">
            </div>

            <!-- Hasta -->
            <div>
                <label class="text-sm text-gray-600">Hasta</label>
                <input type="date"
                       name="hasta"
                       value="{{ request('hasta') }}"
                       class="w-full rounded-lg border-gray-300">
            </div>

            <!-- Tipo de Pago -->
            <div>
                <label class="text-sm text-gray-600">Tipo de Pago</label>
                <select name="tipo_pago_id"
                        class="w-full rounded-lg border-gray-300">
                    <option value="">Todos</option>
                    @foreach($tipoPagos as $tp)
                        <option value="{{ $tp->id }}"
                            @selected(request('tipo_pago_id') == $tp->id)>
                            {{ $tp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label class="text-sm text-gray-600">Estado</label>
                <select name="estado"
                        class="w-full rounded-lg border-gray-300">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('estado')==='1')>
                        Completada
                    </option>
                    <option value="0" @selected(request('estado')==='0')>
                        Anulada
                    </option>
                </select>
            </div>

            <!-- Botón -->
            <div class="flex items-end">
                <button class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                    Filtrar
                </button>
            </div>

        </form>

        <!-- Tarjetas resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Total Vendido</p>
                <p class="text-2xl font-bold text-orange-600">
                    Bs {{ number_format($total ?? 0, 2) }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Total Descuentos</p>
                <p class="text-2xl font-bold text-red-600">
                    - Bs {{ number_format($descuentos ?? 0, 2) }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-5">
                <p class="text-gray-600 text-sm">Cantidad de Ventas</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ isset($ventas) ? $ventas->count() : 0 }}
                </p>
            </div>

        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
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
                        <tr class="border-t hover:bg-gray-50">
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

                            <td class="p-4 text-red-600">
                                - Bs {{ number_format($v->descuento, 2) }}
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
                                class="p-6 text-center text-gray-500">
                                No existen ventas para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</x-layouts.app>
