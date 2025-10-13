<x-layouts.app>
    <div class="flex justify-between">
        <div class="">
            <h1 class=" text-2xl">Gestión de Ventas</h1>
            <p>Registra y gestiona todas las ventas de la pizzería con promociones</p>
        </div>
        <flux:button>+ Nueva Venta</flux:button>
    </div>
    <div class="flex justify-between mt-8 gap-10">


        <div
            class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <a href="#">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total Ventas</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here €68.30</p>
            <small>2 ventas registradas</small>
        </div>


        <div
            class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <a href="#">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Descuentos Aplicados</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here €9.98</p>
            <small>En promociones</small>
        </div>
   <div
            class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <a href="#">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Ventas Completadas</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here 2</p>
            <small>Entregadas</small>
        </div>

         <div
            class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <a href="#">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Venta Promedio</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here €34.15</p>
            <small>Por transacción</small>
        </div>

    </div>






<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    #1000
                </th>
                <td class="px-6 py-4">
                    Juan Perez
                </td>
                <td class="px-6 py-4">
                    15/12/2024
                </td>
                <td class="px-6 py-4">
                    49.88
                </td>
                <td class="px-6 py-4 text-red-500">
                   -9.98
                </td>
                <td class="px-6 py-4">
                    39.90
                </td>
                <td class="px-6 py-4">
                    <flux:badge color="lime">QR</flux:badge>
                </td>
                <td class="px-6 py-4">
                    <flux:badge color="green">Completada</flux:badge>
                </td>
                <td class="px-6 py-4 text-right flex ga">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><flux:icon name="eye" /></a>
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><flux:icon name="trash" /></a>
                </td>
            </tr>
             <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    #1000
                </th>
                <td class="px-6 py-4">
                    Juan Perez
                </td>
                <td class="px-6 py-4">
                    15/12/2024
                </td>
                <td class="px-6 py-4">
                    49.88
                </td>
                <td class="px-6 py-4 text-red-500">
                   -9.98
                </td>
                <td class="px-6 py-4">
                    39.90
                </td>
                <td class="px-6 py-4">
                    <flux:badge color="lime">QR</flux:badge>
                </td>
                <td class="px-6 py-4">
                    <flux:badge color="green">Completada</flux:badge>
                </td>
                <td class="px-6 py-4 text-right flex ga">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><flux:icon name="eye" /></a>
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><flux:icon name="trash" /></a>
                </td>
            </tr>
             <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    #1000
                </th>
                <td class="px-6 py-4">
                    Juan Perez
                </td>
                <td class="px-6 py-4">
                    15/12/2024
                </td>
                <td class="px-6 py-4">
                    49.88
                </td>
                <td class="px-6 py-4 text-red-500">
                   -9.98
                </td>
                <td class="px-6 py-4">
                    39.90
                </td>
                <td class="px-6 py-4">
                    <flux:badge color="lime">QR</flux:badge>
                </td>
                <td class="px-6 py-4">
                    <flux:badge color="green">Completada</flux:badge>
                </td>
                <td class="px-6 py-4 text-right flex ga">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><flux:icon name="eye" /></a>
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><flux:icon name="trash" /></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</x-layouts.app>
