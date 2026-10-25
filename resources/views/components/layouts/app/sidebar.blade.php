@php
    $user = auth()->user();

    $groups = [
        "Inicio" => [
            [
                "name" => "Inicio",
                "icon" => "home",
                "route" => route("dashboard"),
                "current" => request()->routeIs("dashboard"),
                "privilege" => null,
            ],
        ],

        "Ventas" => [
            [
                "name" => "Venta",
                "icon" => "shopping-cart",
                "route" => route("ventas.index"),
                "current" => request()->routeIs("ventas.*"),
                "privilege" => "ver-ventas",
            ],
            [
                "name" => "Cliente",
                "icon" => "users",
                "route" => route("clientes.index"),
                "current" => request()->routeIs("clientes.*"),
                "privilege" => "ver-clientes",
            ],
            [
                "name" => "Festividades",
                "icon" => "home",
                "route" => route("festividades.index"),
                "current" => request()->routeIs("festividades.*"),
                "privilege" => "ver-festividades",
            ],
            [
                "name" => "Promocion",
                "icon" => "light-bulb",
                "route" => route("promociones.index"),
                "current" => request()->routeIs("promociones.*"),
                "privilege" => "ver-promociones",
            ],
            [
                "name" => "Canjes de puntos",
                "icon" => "light-bulb",
                "route" => route("canjes.index"),
                "current" => request()->routeIs("canjes.*"),
                "privilege" => "ver-canjes",
            ],
            [
                "name" => "Productos",
                "icon" => "shopping-bag",
                "route" => route("productos.index"),
                "current" => request()->routeIs("productos.*"),
                "privilege" => "ver-productos",
            ],
            [
                "name" => "Categorias",
                "icon" => "folder-arrow-down",
                "route" => route("categorias.index"),
                "current" => request()->routeIs("categorias.*"),
                "privilege" => "ver-categorias",
            ],
            [
                "name" => "Tipo de pagos",
                "icon" => "queue-list",
                "route" => route("tipo_pagos.index"),
                "current" => request()->routeIs("tipo_pagos.*"),
                "privilege" => "ver-tipos-de-pago",
            ],
        ],

        "Configuraciones" => [
            [
                "name" => "Usuarios",
                "icon" => "user",
                "route" => route("usuarios.index"),
                "current" => request()->routeIs("usuarios.*"),
                "privilege" => "ver-usuarios",
            ],
            [
                "name" => "Roles",
                "icon" => "user-group",
                "route" => route("roles.index"),
                "current" => request()->routeIs("roles.*"),
                "privilege" => "ver-roles",
            ],
            [
                "name" => "Privilegios",
                "icon" => "wrench-screwdriver",
                "route" => route("privilegios.index"),
                "current" => request()->routeIs("privilegios.*"),
                "privilege" => "ver-privilegios",
            ],
        ],

        "Reporteria" => [
            [
                "name" => "Reporte",
                "icon" => "document-chart-bar",
                "route" => route("reportes.index"),
                "current" => request()->routeIs("reportes.*"),
                "privilege" => "ver-reportes",
            ],
        ],
    ];

   
    $groups = collect($groups)
        ->map(function ($links) use ($user) {
            return collect($links)
                ->filter(function ($link) use ($user) {
                    return $link['privilege'] === null
                        || $user->hasPrivilegeSlug($link['privilege']);
                })
                ->values()
                ->all();
        })
        ->filter(fn ($links) => !empty($links))
        ->all();

    $showDefaultPasswordAlert = $user
        && ! request()->routeIs('password.edit')
        && ! session('default_password_alert_shown')
        && $user->hasDefaultPassword();

    if ($showDefaultPasswordAlert) {
        session()->put('default_password_alert_shown', true);
    }

@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-surface-page">
        <flux:sidebar sticky stashable class="app-sidebar border-e border-orange-800 bg-orange-700 dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @foreach($groups as $group => $links)
                    <flux:navlist.group :heading="$group" class="grid">
                        @foreach($links as $link)
                            <flux:navlist.item class="rounded-lg" :icon="$link['icon']" :href="$link['route']" :current="$link['current']" wire:navigate>{{$link['name'] }}</flux:navlist.item>
                        @endforeach
                    </flux:navlist.group>
                @endforeach
            </flux:navlist>

            <flux:spacer />

            {{-- Desktop User Menu --}}
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-orange-100 text-orange-900 dark:bg-orange-900/50 dark:text-orange-200"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        {{-- Mobile User Menu --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-orange-100 text-orange-900 dark:bg-orange-900/50 dark:text-orange-200"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @if ($showDefaultPasswordAlert)
            <div
                id="default-password-alert"
                hidden
                data-update-url="{{ route('password.edit') }}"
                data-title="Actualiza tu contraseña"
                data-text="Estás usando la contraseña por defecto. Debes actualizarla para proteger tu cuenta."
                data-confirm-text="Actualizar contraseña"
                data-cancel-text="Cancelar"
            ></div>
        @endif

        @fluxScripts
    </body>
</html>
