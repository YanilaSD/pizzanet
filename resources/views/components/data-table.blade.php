@props([
    'title' => null,
    'description' => null
])

<div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl shadow-sm mt-8 overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 border-b border-gray-100">
        <div>
            @if($title)
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $title }}
                </h2>
            @endif

            @if($description)
                <p class="text-sm text-gray-500 mt-1">
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
        <table class="w-full text-sm text-left text-gray-600 border-separate border-spacing-0">
            
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr class="[&>th]:px-6 [&>th]:py-3 [&>th]:font-medium">
                    {{ $head }}
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                {{ $body }}
            </tbody>

        </table>
    </div>

    @isset($pagination)
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $pagination }}
        </div>
    @endisset

</div>
