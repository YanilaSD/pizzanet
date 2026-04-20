<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

            <div class="min-h-screen bg-gradient-to-b from-orange-50 to-yellow-50 py-10 px-6">
                <div class="grid md:grid-cols-3 gap-6 max-w-7xl mx-auto">
                    <div class="col-span-2">
                        <h1 class="text-3xl font-bold text-center mb-4">Bienvenido: {{ $cliente->nombre ?? 'Invitado' }}</h1>
                    </div>
                    <div class="col-span-1">
                        <flux:button href="{{ route('pre-dashboard') }}" color="red" type="button" variant="outline" class="w-full">Cerrar Sesión</flux:button>
                    </div>
                    <div class="md:col-span-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Nivel Bronce</h3>
                                <p class="text-4xl font-bold mt-1">{{ $cliente->puntos ?? 0 }}</p>
                                <p class="text-sm opacity-90">Puntos disponibles</p>
                                <p class="text-xs mt-2">Te faltan {{ 500 - ($cliente->puntos ?? 0) }} puntos para canjear una pizza</p>
                            </div>
                            <x-flux::icon name="star" class="w-12 h-12 text-white opacity-90" />
                        </div>

                        <div class="w-full bg-orange-200 rounded-full h-2.5 mt-6">
                            <div class="bg-white h-2.5 rounded-full" style="width: 50%"></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-md p-6">
                        <h3 class="text-2xl font-semibold text-gray-800 flex items-center space-x-2">
                            <x-flux::icon name="chart-bar" class="w-5 h-5 text-green-500" />
                            <span>Mis Estadísticas</span>
                        </h3>
                        <ul class="mt-4 text-gray-600 space-y-2">
                            <li>Total Gastado: <span class="font-semibold text-gray-800">Bs. {{ number_format($totalVentas, 2) }}</span></li>
                            <li>Compras Realizadas: <span class="font-semibold text-gray-800">{{ $comprasRealizadas }}</span></li>
                        </ul>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto mt-10 bg-white rounded-2xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">¿Cómo ganar y usar puntos?</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <p>Por cada Bs. 20 de compra = 1 punto</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-orange-500 rounded-full"></span>
                            <p>Canjea puntos por pizzas, bebidas y más</p>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-3">
                            <p>🍕 500 puntos = 1 Pizza gratis</p>
                            <p>🥤 100 puntos = 1 Bebida gratis</p>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto mt-10">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                        <x-flux::icon name="square-3-stack-3d" class="w-5 h-5 text-orange-500" />
                        <span>Productos Disponibles</span>
                    </h3>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($productos as $producto)
                            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition">
                                <img src="{{ $producto->imagenUrl }}" alt="{{ $producto->nombre }}" class="h-40 w-full object-cover">
                                <div class="p-4">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $producto->nombre }}</h4>
                                    <p class="text-sm text-gray-500">{{ $producto->descripcion }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Categoría: {{ $producto->categoria->nombre }}</p>
                                    <p class="text-sm font-medium text-gray-800 mt-2">Precio: Bs. {{ number_format($producto->precio, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="max-w-7xl mx-auto mt-12">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                        <x-flux::icon name="gift" class="w-5 h-5 text-purple-500" />
                        <span>Promociones Especiales</span>
                    </h3>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($promociones as $promo)
                            <div class="rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 p-4 text-white shadow-md hover:shadow-lg transition">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xl font-semibold">{{ $promo->nombre }}</h4>
                                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">- {{ $promo->descuento }} Bs</span>
                                </div>
                                <p class="text-sm mt-2 opacity-90">Categoria: {{$promo->festividad->nombre}} / {{ $promo->festividad->descripcion }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="max-w-7xl mx-auto mt-12">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                        <x-flux::icon name="clock" class="w-5 h-5 text-green-500" />
                        <span>Historial de Canjes</span>
                    </h3>

                    @include('modules.clientes.partials.canjes-table', ['canjes' => $canjes])
                </div>

            </div>


        @fluxScripts
    </body>
</html>
