<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @include('partials.pwa')
    </head>
    <body class="min-h-screen bg-surface-page">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-b from-orange-50 to-yellow-50 dark:from-zinc-950 dark:to-zinc-900 px-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="w-20 h-20 bg-orange-500 rounded-full flex justify-center items-center">
                    <x-flux::icon name="building-storefront" class="w-10 h-10 text-white" />
                </div>

                <div class="text-center">
                    <h1 class="text-3xl font-bold text-zinc-800 dark:text-white">Pizzería Yuneth SRL</h1>
                    <p class="text-lg text-zinc-500 dark:text-zinc-300">Portal de clientes</p>
                </div>
            </div>

            <div class="mt-8 w-full max-w-md rounded-2xl bg-white p-6 shadow-lg dark:bg-zinc-900">
                @if ($errors->any())
                    <flux:callout variant="danger" icon="x-circle" heading="{{ $errors->first() }}" class="mb-4" />
                @endif

                <form method="POST" action="{{ route('clientes.search') }}" autocomplete="off">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Consulta de cliente</flux:heading>
                            <flux:text class="mt-2">Ingresa tu CI para ver tus puntos.</flux:text>
                        </div>

                        <flux:input
                            label="CI del cliente"
                            name="ci"
                            type="number"
                            placeholder="Ingresa el CI del cliente"
                            value="{{ old('ci') }}"
                        />

                        <flux:button type="submit" color="orange" variant="primary" class="w-full">Consultar</flux:button>
                    </div>
                </form>
            </div>
        </div>

        @fluxScripts
    </body>
</html>
