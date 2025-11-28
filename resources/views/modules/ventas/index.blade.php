<x-layouts.app>
    <div class="flex justify-between">
        <div class="">
            <h1 class=" text-2xl">Gestión de Ventas</h1>
            <p>Registra y gestiona todas las ventas de la pizzería con promociones</p>
        </div>
        <flux:button variant="primary" color="green" href="{{ route('ventas.create') }}">
            Nueva venta
        </flux:button>
    </div>

<div class="flex gap-6 overflow-x-auto py-4 scrollbar-hide">
    <div class="min-w-[300px] bg-white rounded-xl shadow-md flex items-center p-4 border border-gray-200">
        
        <img 
            src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2340&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
            class="w-24 h-24 rounded-lg object-cover"
            alt="Pizza Ventas"
        />

        <div class="ml-4">
            <h3 class="text-lg font-bold text-gray-800">Total Ventas</h3>
            <p class="text-2xl font-extrabold text-orange-600">Bs {{ $ventasTotal }}</p>
            <p class="text-sm text-gray-500">{{ $ventasCompletadas }} ventas registradas</p>
        </div>
    </div>

    <div class="min-w-[300px] bg-white rounded-xl shadow-md flex items-center p-4 border border-gray-200">
        
        <img 
            src="https://images.unsplash.com/photo-1655673654158-9f7285b7d1ea?q=80&w=1364&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
            class="w-24 h-24 rounded-lg object-cover"
            alt="Descuentos"
        />

        <div class="ml-4">
            <h3 class="text-lg font-bold text-gray-800">Descuentos Aplicados</h3>
            <p class="text-2xl font-extrabold text-red-600">Bs {{ $descuentoTotal }}</p>
            <p class="text-sm text-gray-500">En promociones</p>
        </div>
    </div>

    <div class="min-w-[300px] bg-white rounded-xl shadow-md flex items-center p-4 border border-gray-200">

        <img 
            src="https://images.unsplash.com/photo-1594007654729-407eedc4be65?auto=format&fit=crop&w=300&q=60" 
            class="w-24 h-24 rounded-lg object-cover"
            alt="Ventas Completadas"
        />

        <div class="ml-4">
            <h3 class="text-lg font-bold text-gray-800">Ventas Completadas</h3>
            <p class="text-2xl font-extrabold text-green-600">{{ $ventasCompletadas }}</p>
            <p class="text-sm text-gray-500">Entregadas</p>
        </div>
    </div>

    <div class="min-w-[300px] bg-white rounded-xl shadow-md flex items-center p-4 border border-gray-200">

        <img 
            src="https://images.unsplash.com/photo-1571066811602-716837d681de?q=80&w=868&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
            class="w-24 h-24 rounded-lg object-cover"
            alt="Venta Promedio"
        />

        <div class="ml-4">
            <h3 class="text-lg font-bold text-gray-800">Venta Promedio</h3>
            <p class="text-2xl font-extrabold text-blue-600">Bs {{ $ventasPromedio }}</p>
            <p class="text-sm text-gray-500">Por transacción</p>
        </div>
    </div>

</div>


<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
    <table class="w-full bg-white text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
            Registro de Ventas
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Historial completo de todas las ventas realizadas con promociones aplicadas</p>
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                   ID Venta
                </th>
                <th scope="col" class="px-6 py-3">
                   Cliente
                </th>
                <th scope="col" class="px-6 py-3">
                    Fecha
                </th>
                <th scope="col" class="px-6 py-3">
                    Subtotal
                </th>
                <th scope="col" class="px-6 py-3">
                    Descuento
                </th>
                <th scope="col" class="px-6 py-3">
                    Total
                </th>
                <th scope="col" class="px-6 py-3">
                    Metodo de Pago
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
            @forelse ($ventas as $venta)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        #{{ $venta->id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $venta->cliente->nombre ?? 'Sin nombre' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ number_format($venta->subtotal, 2) }}
                    </td>
                    <td class="px-6 py-4 text-red-500">
                        -{{ number_format($venta->descuento, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ number_format($venta->total, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:badge color="lime">{{ $venta->tipoPago->nombre }}</flux:badge>
                    </td>
                    <td class="px-6 py-4">
                        <flux:badge color="{{ $venta->estado == '1' ? 'green' : 'yellow' }}">
                            {{ $venta->estado == '1' ? 'Completada' : 'Pendiente' }}
                        </flux:badge>
                    </td>
                    <td class="px-6 py-4 text-right flex gap-2">
                        <a href="{{ route('ventas.show', $venta) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            <flux:icon name="eye" />
                        </a>
                        <form action="{{ route('ventas.destroy', $venta) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                <flux:icon name="trash" />
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-gray-500 dark:text-gray-400">
                        No se han registrado ventas aún.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 bg-white">
        {{ $ventas->links() }}
    </div>
</div>

</x-layouts.app>
