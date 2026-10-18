<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen relative overflow-hidden">

        <video
            autoplay
            muted
            loop
            playsinline
            class="absolute inset-0 w-full h-full object-cover"
        >
            <source src="{{ asset('videos/login_background.mp4') }}" type="video/mp4">
            Tu navegador no soporta videos HTML5.
        </video>

        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-sm">
                <a href="{{ route('login') }}"
                class="flex flex-col items-center gap-2 font-medium"
                wire:navigate>
                    <span class="flex h-26 w-26 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-26 fill-current text-white" />
                    </span>
                    <span class="sr-only">
                        {{ config('app.name', 'Pizzeria') }}
                    </span>
                </a>

                <div class="mt-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>