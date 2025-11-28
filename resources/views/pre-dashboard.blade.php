<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-b from-orange-50 to-yellow-50">

            <!-- Logo -->
            <div class="flex flex-col items-center space-y-4">
                <div class="w-20 h-20 bg-orange-500 rounded-full flex justify-center items-center">
                    <x-flux::icon name="building-storefront" class="w-10 h-10 text-white" />
                </div>

                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800">Pizzería Yuneth SRL</h1>
                    <p class="text-gray-500 text-lg">Sistema de Gestión y Fidelización</p>
                    <p class="text-gray-400 text-sm">Cotoca, Bolivia</p>
                </div>
            </div>

            <div class="mt-6 w-full max-w-3xl px-6">
                @if ($errors->any())
                    <flux:heading size="md" class="text-red-600 mb-2">Datos del cliente:</flux:heading>
                    <div class="my-4 gap-4 space-y-2">
                        <flux:callout variant="danger" icon="x-circle" heading="Error en los datos del cliente" />
                    </div>
                @endif
            </div>
            

            <!-- Cards -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl w-full px-6">

                <flux:button
                    href="{{ route('login') }}"
                    class="group block w-full text-left rounded-2xl h-full bg-gradient-to-r from-orange-500 to-red-500 p-6 shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl"
                >
                    <div class="flex flex-col h-full justify-center">
                        <div class="flex items-center space-x-2 mb-2">
                            <x-flux::icon name="user" class="w-6 h-6 text-white" />
                            <h2 class="text-2xl font-semibold text-white">Panel Administrativo</h2>
                        </div>
                        <p class="text-orange-100">Gestión del sistema</p>
                    </div>
                </flux:button>

                <flux:modal.trigger name="searchClientModal" class="w-full">
                    <flux:button class="group block w-full h-full text-left rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 p-6 shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                        <div class="flex flex-col h-full justify-center">
                            <div class="flex items-center space-x-2 mb-2">
                                <x-flux::icon name="star" class="w-6 h-6 text-white" />
                                <h2 class="text-2xl font-semibold text-white">Portal de Clientes</h2>
                            </div>
                            <p class="text-purple-100">Canjea tus puntos</p>
                        </div>
                    </flux:button>
                </flux:modal.trigger>
            </div>

            
    
            <flux:modal name="searchClientModal" class="w-full" class="md:w-96">
                <form method="POST" action="{{ route('clientes.search') }}" autocomplete="off">
                    @csrf
                    <h2 class="text-xl font-semibold mb-4">Buscar Cliente</h2>
                    <div class="mb-4">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Consulta de Cliente</flux:heading>
                                <flux:text class="mt-2">Ingresa tus datos.</flux:text>
                            </div>

                            <flux:input label="CI del Ciente" name="ci" type="number" placeholder="Ingresa el ci del cliente" />

                            <div class="flex">
                                <flux:spacer />
                                <flux:button type="submit" color="orange" variant="primary">Consultar</flux:button>
                            </div>
                        </div>
                    </div>
                </form>
            </flux:modal>
        </div>


        @fluxScripts
    </body>
</html>