@props([
    'title' => null,
    'description' => null,
])

<x-module-card-lg class="mt-8 overflow-hidden p-0">
    <div class="flex flex-col gap-4 border-b border-zinc-200 p-6 dark:border-zinc-800 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0 shrink">
            @if($title)
                <h2 class="module-card-title">
                    {{ $title }}
                </h2>
            @endif

            @if($description)
                <p class="module-card-sublabel mt-1">
                    {{ $description }}
                </p>
            @endif
        </div>

        @isset($actions)
            <div class="w-full min-w-0 lg:max-w-md lg:shrink-0 xl:max-w-lg">
                {{ $actions }}
            </div>
        @endisset
    </div>

    <div class="module-table-scroll">
        {{-- border-collapse: con border-separate los bordes de <tr> (divide-y) no se dibujan --}}
        <table class="w-full border-collapse text-left text-sm text-zinc-600 dark:text-white">
            <thead class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-300">
                <tr class="[&>th]:sticky [&>th]:top-0 [&>th]:z-10 [&>th]:bg-zinc-50 [&>th]:px-6 [&>th]:py-3 [&>th]:font-medium dark:[&>th]:bg-zinc-950">
                    {{ $head }}
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 [&>tr]:transition-colors [&>tr:hover]:bg-zinc-50 dark:[&>tr:hover]:bg-zinc-800/50">
                {{ $body }}
            </tbody>
        </table>
    </div>

    @isset($pagination)
        <div class="border-t border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-800 dark:bg-zinc-950/30">
            {{ $pagination }}
        </div>
    @endisset
</x-module-card-lg>
