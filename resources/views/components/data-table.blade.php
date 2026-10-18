@props([
    'title' => null,
    'description' => null
])

<x-module-card-lg class="mt-8 overflow-hidden !p-0">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 p-6 dark:border-gray-700">
        <div>
            @if($title)
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
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
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endisset

    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-0 text-left text-sm text-gray-600 dark:text-gray-300">
            
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                <tr class="[&>th]:px-6 [&>th]:py-3 [&>th]:font-medium">
                    {{ $head }}
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                {{ $body }}
            </tbody>

        </table>
    </div>

    @isset($pagination)
        <div class="border-t border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-900/20">
            {{ $pagination }}
        </div>
    @endisset

</x-module-card-lg>
