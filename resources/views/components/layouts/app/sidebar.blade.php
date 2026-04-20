@php
    $groups = [
        "Inicio" => [
            [
                "name" => "Inicio",
                "icon" => "home",
                "route" => route("dashboard"),
                "current" => request()->routeIs("dashboard")
            ]
        ],
        "Ventas" => [
            [
                "name" => "Venta",
                "icon" => "shopping-cart",
                "route" => route("ventas.index"),
                "current" => request()->routeIs("login")
            ],
             [
                "name" => "Cliente",
                "icon" => "users",
                "route" => route("clientes.index"),
                "current" => request()->routeIs("login")
            ],
            [
                "name" => "Festividades",
                "icon" => "home",
                "route" => route("festividades.index"),
                "current" => request()->routeIs("festividades.index")
            ],

            [
                "name" => "Promocion",
                "icon" => "home",
                "route" => route("promociones.index"),
                "current" => request()->routeIs("promociones.index")
            ],
            [
                "name" => "Descuentos",
                "icon" => "home",
                "route" => route("descuentos.index"),
                "current" => request()->routeIs("descuentos.index")
            ],
            [
                "name" => "Productos",
                "icon" => "shopping-bag",
                "route" => route("productos.index"),
                "current" => request()->routeIs("productos.index")
            ],
               [
                "name" => "Categorias",
                "icon" => "shopping-bag",
                "route" => route("categorias.index"),
                "current" => request()->routeIs("categorias.index")
            ],
            [
                "name" => "Tipo de pagos",
                "icon" => "shopping-bag",
                "route" => route("tipo_pagos.index"),
                "current" => request()->routeIs("tipo_pagos")
            ]
        ],
        "Configuraciones" => [
            [
                "name" => "Usuarios",
                "icon" => "user",
                "route" => route("usuarios.index"),
                "current" => request()->routeIs("usuarios.index")
            ],
            [
                "name" => "Roles",
                "icon" => "users",
                "route" => route("roles.index"),
                "current" => request()->routeIs("roles.index")
            ],
            [
                "name" => "Privilegios",
                "icon" => "adjustments-horizontal",
                "route" => route("privilegios.index"),
                "current" => request()->routeIs("privilegios.index")
            ]
        ],
        "Reporteria" => [
            [
                "name" => "Reporte",
                "icon" => "document-text",
                "route" => route("reportes.index"),
                "current" => request()->routeIs("reportes.index")
            ]
        ]
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#dfdbdb] dark:bg-[#3b1f1f]">
        <flux:sidebar sticky stashable class="border-e border-[#d4a373] bg-orange-600 dark:border-[#a0522d] dark:bg-[#4e342e]">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @foreach($groups as $group => $links)
                    <flux:navlist.group :heading="$group" class="grid">
                        @foreach($links as $link)
                            <flux:navlist.item class="rounded-lg text-white hover:bg-zinc-100 dark:hover:bg-zinc-800" :icon="$link['icon']" :href="$link['route']" :current="$link['current']" wire:navigate>{{$link['name'] }}</flux:navlist.item>
                        @endforeach
                    </flux:navlist.group>
                @endforeach
            </flux:navlist>

            <flux:spacer />

           <!--   <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
                -->
            <!-- Desktop User Menu -->
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
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-[#ffccbc] text-black dark:bg-[#8d6e63] dark:text-white"
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

        <!-- Mobile User Menu -->
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
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-[#ffccbc] text-black dark:bg-[#8d6e63] dark:text-white"
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

        @fluxScripts
    </body>
</html>
