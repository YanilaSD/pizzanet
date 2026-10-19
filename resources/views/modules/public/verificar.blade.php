@php
    $saldo = (int) $cliente->saldo_puntos;
    $canjePuntos = (int) ($canje->puntos ?? 100);
    $canjeDescuento = (float) ($canje->descuento ?? 10);
    $base = max($canjePuntos, 1);
    $bloques = intdiv($saldo, $base);
    $enBloque = $saldo % $base;
    $descuentoDisponible = round($bloques * $canjeDescuento, 2);
    $faltan = $enBloque === 0 && $saldo > 0 ? $base : $base - $enBloque;
    if ($saldo === 0) {
        $faltan = $base;
    }
    $progreso = $saldo === 0
        ? 0
        : ($enBloque === 0 ? 100 : ($enBloque / $base) * 100);
    $nombreCorto = collect(explode(' ', trim((string) ($cliente->nombre ?? 'Invitado'))))->first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
        @include('partials.pwa')
        <style>
            [x-cloak]{display:none!important}
            .portal-hero-grid{display:grid;gap:1rem}
            @media (min-width: 640px){
                .portal-hero-grid{grid-template-columns:minmax(0,2fr) minmax(0,1fr)}
            }
        </style>
    </head>
    <body class="min-h-screen bg-gradient-to-b from-orange-50 to-yellow-50 text-zinc-900 dark:from-zinc-950 dark:to-zinc-900 dark:text-white">
        <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 py-3 sm:px-6">
                <div class="flex min-w-0 shrink-0 items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-orange-500 shadow-lg shadow-orange-500/25">
                        <x-flux::icon name="building-storefront" class="h-5 w-5 text-white" />
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-zinc-900 dark:text-white">Pizzería Yuneth</p>
                        <p class="hidden truncate text-xs text-zinc-500 sm:block dark:text-zinc-400">Hola, {{ $nombreCorto }}</p>
                    </div>
                </div>

                <nav class="flex min-w-0 flex-1 items-center justify-center gap-1 overflow-x-auto rounded-full bg-zinc-100 p-1 text-xs font-medium dark:bg-zinc-900">
                    <a href="#puntos" class="rounded-full px-3 py-1.5 whitespace-nowrap text-zinc-600 transition hover:bg-white hover:text-orange-600 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-orange-400">Puntos</a>
                    <a href="#menu" class="rounded-full px-3 py-1.5 whitespace-nowrap text-zinc-600 transition hover:bg-white hover:text-orange-600 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-orange-400">Menú</a>
                    <a href="#promos" class="rounded-full px-3 py-1.5 whitespace-nowrap text-zinc-600 transition hover:bg-white hover:text-orange-600 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-orange-400">Promos</a>
                    <a href="#movimientos" class="rounded-full px-3 py-1.5 whitespace-nowrap text-zinc-600 transition hover:bg-white hover:text-orange-600 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-orange-400">Historial</a>
                </nav>

                <div class="flex shrink-0 items-center gap-1.5">
                    @unless ($cliente->esAnonimo())
                        @include('partials.cliente-push')
                    @endunless

                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full text-zinc-500 transition hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800"
                        title="Cambiar tema"
                        aria-label="Cambiar tema"
                        x-data
                        x-on:click="$flux.appearance = document.documentElement.classList.contains('dark') ? 'light' : 'dark'"
                    >
                        <x-flux::icon name="moon" class="h-5 w-5 dark:hidden" />
                        <x-flux::icon name="sun" class="h-5 w-5 hidden dark:block" />
                    </button>

                    <form method="POST" action="{{ route('cliente.portal.salir') }}">
                        @csrf
                        <flux:button color="red" type="submit" variant="ghost" size="sm">Salir</flux:button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 pb-16 pt-6 sm:px-6">
            <section class="portal-hero-grid">
                <article
                    class="overflow-hidden rounded-2xl shadow-xl"
                    style="background-image: url('https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center;"
                >
                    <div class="flex h-full flex-col justify-between px-5 py-7 sm:px-8 sm:py-8" style="background: linear-gradient(90deg, rgba(9,9,11,.92), rgba(67,20,7,.55));">

                        <div class="mt-6 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-orange-200">Puntos disponibles</p>
                                <p class="mt-1 text-5xl font-extrabold leading-none text-white">{{ $saldo }}</p>
                                <p class="mt-2 text-sm text-zinc-200">
                                    @if ($bloques >= 1)
                                        Puedes canjear Bs {{ number_format($descuentoDisponible, 2) }} ahora
                                        @if ($enBloque > 0)
                                            · te faltan {{ $faltan }} pts para otro bloque
                                        @endif
                                    @else
                                        Te faltan {{ $faltan }} pts para Bs {{ number_format($canjeDescuento, 2) }} de descuento
                                    @endif
                                </p>
                            </div>
                            <div class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15 sm:flex">
                                <x-flux::icon name="star" class="h-6 w-6 text-yellow-200" />
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="h-2 overflow-hidden rounded-full bg-white/15">
                                <div class="h-full rounded-full bg-yellow-300" style="width: {{ min(100, max(0, $progreso)) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-orange-100">
                                {{ $canjePuntos }} pts = Bs {{ number_format($canjeDescuento, 2) }} · el resto se guarda
                            </p>
                        </div>
                    </div>
                </article>

                <article class="relative overflow-hidden rounded-2xl p-5 text-white shadow-lg" style="background: linear-gradient(135deg, #f97316, #ea580c 55%, #dc2626);">
                    <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-10 right-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-xs font-semibold uppercase tracking-widest text-orange-100">Este mes</p>
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/15">
                                <x-flux::icon name="chart-bar" class="h-5 w-5 text-white" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <p class="text-4xl font-extrabold leading-none sm:text-5xl">Bs {{ number_format($totalVentas, 2) }}</p>
                            <p class="mt-2 text-sm text-orange-50">
                                {{ $comprasRealizadas }} {{ (int) $comprasRealizadas === 1 ? 'compra' : 'compras' }}
                            </p>
                        </div>
                    </div>
                </article>
            </section>

            <section id="puntos" class="mt-10 scroll-mt-24">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">¿Cómo ganar y usar puntos?</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Reglas reales del programa. Pide el canje al pagar, no desde aquí.</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">1</span>
                        <h3 class="mt-3 font-semibold text-zinc-900 dark:text-white">Cada Bs 10 = 1 pt</h3>
                        <p class="mt-1 text-sm leading-6 text-zinc-500 dark:text-zinc-400">Se calcula sobre el total pagado, redondeado hacia abajo. Bs 29 suman 2 pts, no 3.</p>
                    </article>
                    <article class="rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700 dark:bg-sky-950 dark:text-sky-300">2</span>
                        <h3 class="mt-3 font-semibold text-zinc-900 dark:text-white">Identifícate con tu CI</h3>
                        <p class="mt-1 text-sm leading-6 text-zinc-500 dark:text-zinc-400">Solo las compras a tu nombre acumulan. Pide que registren tu carnet antes de cobrar.</p>
                    </article>
                    <article class="rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-orange-100 text-sm font-bold text-orange-700 dark:bg-orange-950 dark:text-orange-300">3</span>
                        <h3 class="mt-3 font-semibold text-zinc-900 dark:text-white">Canjea en bloques</h3>
                        <p class="mt-1 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                            {{ $canjePuntos }} pts = Bs {{ number_format($canjeDescuento, 2) }} de descuento.
                            Si tienes 250 pts, canjeas 200 y te quedan 50.
                        </p>
                    </article>
                    <article class="rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-violet-100 text-sm font-bold text-violet-700 dark:bg-violet-950 dark:text-violet-300">4</span>
                        <h3 class="mt-3 font-semibold text-zinc-900 dark:text-white">Promo o puntos</h3>
                        <p class="mt-1 text-sm leading-6 text-zinc-500 dark:text-zinc-400">Una sola ventaja por ticket. Los puntos se acreditan cuando la venta queda completada.</p>
                    </article>
                </div>
            </section>

            <section
                id="menu"
                class="mt-12 scroll-mt-24"
                x-data="{ categoria: 'todas' }"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Menú</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Elige lo que quieras y pídelo en caja. Cada producto muestra los puntos que suma.</p>
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $productos->count() }} {{ $productos->count() === 1 ? 'producto' : 'productos' }}</p>
                </div>

                @if ($categorias->isNotEmpty())
                    <div class="sticky top-16 z-30 mt-4 flex gap-2 overflow-x-auto bg-gradient-to-b from-orange-50 to-orange-50/90 py-2 dark:from-zinc-950 dark:to-zinc-950/90">
                        <button
                            type="button"
                            class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition"
                            :class="categoria === 'todas' ? 'bg-orange-500 text-white shadow-sm' : 'bg-white text-zinc-600 ring-1 ring-zinc-200 hover:bg-orange-50 dark:bg-zinc-900 dark:text-zinc-300 dark:ring-zinc-700'"
                            @click="categoria = 'todas'"
                        >
                            Todos
                        </button>
                        @foreach ($categorias as $categoria)
                            <button
                                type="button"
                                class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition"
                                :class="categoria === '{{ $categoria->id }}' ? 'bg-orange-500 text-white shadow-sm' : 'bg-white text-zinc-600 ring-1 ring-zinc-200 hover:bg-orange-50 dark:bg-zinc-900 dark:text-zinc-300 dark:ring-zinc-700'"
                                @click="categoria = '{{ $categoria->id }}'"
                            >
                                {{ $categoria->nombre }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="mt-5 space-y-8">
                    @php
                        $sinCategoria = $productos->filter(fn ($producto) => ! $producto->categoria);
                    @endphp
                    @forelse ($categorias as $categoria)
                        @php
                            $items = $productos->where('categoria_id', $categoria->id);
                        @endphp
                        @continue($items->isEmpty())

                        <div x-show="categoria === 'todas' || categoria === '{{ $categoria->id }}'" x-cloak>
                            <h3 class="mb-3 text-sm font-semibold uppercase tracking-widest text-zinc-400">{{ $categoria->nombre }}</h3>
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach ($items as $producto)
                                    @php
                                        $stock = (int) ($producto->inventario?->cantidad ?? 0);
                                        $disponible = $stock > 0;
                                        $puntosProducto = \App\Models\Cliente::calcularPuntos((float) $producto->precio);
                                    @endphp
                                    <article class="flex gap-4 rounded-2xl border border-zinc-200 bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                                        <div class="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl bg-orange-100 dark:bg-zinc-800 sm:h-28 sm:w-28">
                                            <img
                                                src="{{ $producto->imagenUrl }}"
                                                alt=""
                                                class="h-full w-full object-cover {{ $producto->imagenUrl ? '' : 'hidden' }}"
                                                onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                            >
                                            <div class="{{ $producto->imagenUrl ? 'hidden' : 'flex' }} h-full w-full items-center justify-center text-orange-400">
                                                <x-flux::icon name="building-storefront" class="h-8 w-8" />
                                            </div>
                                            @unless ($disponible)
                                                <span class="absolute inset-x-2 bottom-2 rounded-full bg-zinc-950/80 px-2 py-0.5 text-center text-[10px] font-semibold uppercase tracking-wide text-white">Agotado</span>
                                            @endunless
                                        </div>
                                        <div class="flex min-w-0 flex-1 flex-col justify-between py-0.5">
                                            <div>
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="font-semibold text-zinc-900 dark:text-white">{{ $producto->nombre }}</h4>
                                                    <p class="shrink-0 text-base font-bold text-orange-600 dark:text-orange-400">Bs {{ number_format($producto->precio, 2) }}</p>
                                                </div>
                                                <p class="mt-1 text-sm leading-5 text-zinc-500 dark:text-zinc-400">
                                                    {{ $producto->descripcion ?: 'Disponible para pedir en caja.' }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                                <span class="rounded-full bg-orange-50 px-2 py-1 font-medium text-orange-700 dark:bg-orange-950/50 dark:text-orange-300">+{{ $puntosProducto }} pts</span>
                                                <span class="rounded-full bg-zinc-100 px-2 py-1 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ $disponible ? 'Disponible' : 'Sin stock' }}</span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        @if ($sinCategoria->isEmpty())
                            <div class="rounded-2xl border border-dashed border-zinc-300 bg-white px-6 py-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
                                <p class="font-medium text-zinc-700 dark:text-zinc-200">Todavía no hay productos publicados.</p>
                                <p class="mt-1 text-sm text-zinc-500">Vuelve más tarde para ver el menú.</p>
                            </div>
                        @endif
                    @endforelse

                    @if ($sinCategoria->isNotEmpty())
                        <div x-show="categoria === 'todas'" x-cloak>
                            <h3 class="mb-3 text-sm font-semibold uppercase tracking-widest text-zinc-400">Otros</h3>
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach ($sinCategoria as $producto)
                                    @php
                                        $stock = (int) ($producto->inventario?->cantidad ?? 0);
                                        $disponible = $stock > 0;
                                        $puntosProducto = \App\Models\Cliente::calcularPuntos((float) $producto->precio);
                                    @endphp
                                    <article class="flex gap-4 rounded-2xl border border-zinc-200 bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                                        <div class="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl bg-orange-100 dark:bg-zinc-800 sm:h-28 sm:w-28">
                                            <img
                                                src="{{ $producto->imagenUrl }}"
                                                alt=""
                                                class="h-full w-full object-cover {{ $producto->imagenUrl ? '' : 'hidden' }}"
                                                onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                            >
                                            <div class="{{ $producto->imagenUrl ? 'hidden' : 'flex' }} h-full w-full items-center justify-center text-orange-400">
                                                <x-flux::icon name="building-storefront" class="h-8 w-8" />
                                            </div>
                                        </div>
                                        <div class="flex min-w-0 flex-1 flex-col justify-between py-0.5">
                                            <div>
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="font-semibold text-zinc-900 dark:text-white">{{ $producto->nombre }}</h4>
                                                    <p class="shrink-0 text-base font-bold text-orange-600 dark:text-orange-400">Bs {{ number_format($producto->precio, 2) }}</p>
                                                </div>
                                                <p class="mt-1 text-sm leading-5 text-zinc-500 dark:text-zinc-400">
                                                    {{ $producto->descripcion ?: 'Disponible para pedir en caja.' }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                                <span class="rounded-full bg-orange-50 px-2 py-1 font-medium text-orange-700 dark:bg-orange-950/50 dark:text-orange-300">+{{ $puntosProducto }} pts</span>
                                                <span class="rounded-full bg-zinc-100 px-2 py-1 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ $disponible ? 'Disponible' : 'Sin stock' }}</span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <section id="promos" class="mt-12 scroll-mt-24">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Promociones</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Ofertas vigentes. Pídelas en caja; no se combinan con el canje de puntos.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($promociones as $promo)
                        <article class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                            <div
                                class="relative h-28 bg-cover bg-center"
                                style="background-image: url('https://images.unsplash.com/photo-1594007654729-407eedc4be65?auto=format&fit=crop&w=1200&q=80');"
                            >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/10"></div>
                                <div class="absolute left-4 top-4 rounded-full bg-white px-3 py-1 text-sm font-bold text-orange-600">
                                    -{{ rtrim(rtrim(number_format((float) $promo->descuento, 2), '0'), '.') }}%
                                </div>
                                <p class="absolute bottom-3 left-4 text-xs font-medium uppercase tracking-wide text-orange-100">
                                    {{ $promo->festividad->nombre ?? 'Promoción' }}
                                </p>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $promo->nombre }}</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $promo->festividad->descripcion ?? 'Válida en compras que cumplan el mínimo.' }}
                                </p>
                                <dl class="mt-3 grid grid-cols-2 gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                                    <div class="rounded-xl bg-zinc-50 px-3 py-2 dark:bg-zinc-800/70">
                                        <dt class="uppercase tracking-wide">Mínimo</dt>
                                        <dd class="mt-0.5 font-semibold text-zinc-800 dark:text-zinc-100">Bs {{ number_format((float) $promo->compra_minima, 2) }}</dd>
                                    </div>
                                    <div class="rounded-xl bg-zinc-50 px-3 py-2 dark:bg-zinc-800/70">
                                        <dt class="uppercase tracking-wide">Vigencia</dt>
                                        <dd class="mt-0.5 font-semibold text-zinc-800 dark:text-zinc-100">
                                            {{ $promo->fecha_inicio?->format('d/m') }} – {{ $promo->fecha_fin?->format('d/m') }}
                                        </dd>
                                    </div>
                                </dl>
                                <p class="mt-3 text-xs font-medium text-orange-600 dark:text-orange-400">Pídela en caja al armar tu pedido</p>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-zinc-300 bg-white px-6 py-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
                            <p class="font-medium text-zinc-700 dark:text-zinc-200">No hay promociones activas ahora.</p>
                            <p class="mt-1 text-sm text-zinc-500">Podés seguir sumando puntos con tu compra.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section id="movimientos" class="mt-12 scroll-mt-24">
                <h2 class="mb-1 text-xl font-bold text-zinc-900 dark:text-white">Historial de movimientos</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Puntos ganados por compras y canjes aplicados en caja.</p>
                @include('modules.clientes.partials.movimientos-table', ['movimientos' => $movimientos])
            </section>
        </main>

        @fluxScripts
    </body>
</html>
