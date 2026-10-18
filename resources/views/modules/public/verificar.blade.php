<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#dfdbdb] dark:bg-[#3b1f1f]">

            <div class="min-h-screen py-10 px-6">
                <div class="grid md:grid-cols-3 gap-6 max-w-7xl mx-auto">
                    <div class="col-span-2">
                        <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-4">Bienvenido: {{ $cliente->nombre ?? 'Invitado' }}</h1>
                    </div>
                    <div class="col-span-1">
                        <flux:button href="{{ route('pre-dashboard') }}" color="red" type="button" variant="outline" class="w-full">Cerrar Sesión</flux:button>
                    </div>

                    <x-module-card-lg class="md:col-span-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="module-card-label">Nivel Bronce</p>
                                <p class="mt-1 text-4xl font-bold text-orange-600 dark:text-orange-500">{{ $cliente->puntos ?? 0 }}</p>
                                <p class="module-card-sublabel mt-1">Puntos disponibles</p>
                                <p class="module-card-sublabel mt-2">Te faltan {{ 500 - ($cliente->puntos ?? 0) }} puntos para canjear una pizza</p>
                            </div>
                            <x-flux::icon name="star" class="w-12 h-12 text-orange-500 opacity-90" />
                        </div>

                        <div class="mt-6 h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-2.5 rounded-full bg-orange-500" style="width: 50%"></div>
                        </div>
                    </x-module-card-lg>

                    <x-module-card-lg>
                        <h3 class="module-card-label flex items-center space-x-2 text-base font-semibold text-gray-900 dark:text-white">
                            <x-flux::icon name="chart-bar" class="w-5 h-5 text-green-500" />
                            <span>Mis Estadísticas</span>
                        </h3>
                        <ul class="mt-4 space-y-2">
                            <li class="module-card-label">Total Gastado: <span class="font-semibold text-gray-900 dark:text-white">Bs. {{ number_format($totalVentas, 2) }}</span></li>
                            <li class="module-card-label">Compras Realizadas: <span class="font-semibold text-gray-900 dark:text-white">{{ $comprasRealizadas }}</span></li>
                        </ul>
                    </x-module-card-lg>
                </div>

                <x-module-card-lg class="mx-auto mt-10 max-w-7xl">
                    <h3 class="module-card-label text-base font-semibold text-gray-900 dark:text-white">¿Cómo ganar y usar puntos?</h3>
                    <div class="mt-4 grid gap-4 text-sm md:grid-cols-2">
                        <div class="flex items-center space-x-2">
                            <span class="h-3 w-3 rounded-full bg-green-500"></span>
                            <p class="module-card-label">Por cada Bs. 20 de compra = 1 punto</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="h-3 w-3 rounded-full bg-orange-500"></span>
                            <p class="module-card-label">Canjea puntos por pizzas, bebidas y más</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap justify-between gap-3 text-sm">
                        <p class="module-card-sublabel">🍕 500 puntos = 1 Pizza gratis</p>
                        <p class="module-card-sublabel">🥤 100 puntos = 1 Bebida gratis</p>
                    </div>
                </x-module-card-lg>

                <div class="mx-auto mt-10 max-w-7xl">
                    <h3 class="module-card-label mb-4 flex items-center space-x-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-flux::icon name="square-3-stack-3d" class="w-5 h-5 text-orange-500" />
                        <span>Productos Disponibles</span>
                    </h3>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($productos as $producto)
                            <x-module-card-lg class="overflow-hidden !p-0 transition hover:shadow-md">
                                <img src="{{ $producto->imagenUrl }}" alt="{{ $producto->nombre }}" class="h-40 w-full object-cover">
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $producto->nombre }}</h4>
                                    <p class="module-card-sublabel">{{ $producto->descripcion }}</p>
                                    <p class="module-card-sublabel mt-1">Categoría: {{ $producto->categoria->nombre }}</p>
                                    <p class="module-card-label mt-2">Precio: Bs. {{ number_format($producto->precio, 2) }}</p>
                                </div>
                            </x-module-card-lg>
                        @endforeach
                    </div>
                </div>

                <div class="mx-auto mt-12 max-w-7xl">
                    <h3 class="module-card-label mb-4 flex items-center space-x-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-flux::icon name="gift" class="w-5 h-5 text-purple-500" />
                        <span>Promociones Especiales</span>
                    </h3>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($promociones as $promo)
                            <x-module-card>
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $promo->nombre }}</h4>
                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">- {{ $promo->descuento }} Bs</span>
                                </div>
                                <p class="module-card-sublabel mt-2">Categoria: {{ $promo->festividad->nombre }} / {{ $promo->festividad->descripcion }}</p>
                            </x-module-card>
                        @endforeach
                    </div>
                </div>

                <div class="mx-auto mt-12 max-w-7xl">
                    <h3 class="module-card-label mb-4 flex items-center space-x-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-flux::icon name="clock" class="w-5 h-5 text-green-500" />
                        <span>Historial de Canjes</span>
                    </h3>

                    @include('modules.clientes.partials.canjes-table', ['canjes' => $canjes])
                </div>

            </div>


        @fluxScripts
    </body>
</html>
