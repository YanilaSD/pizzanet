<x-module-card-lg class="flex items-center justify-between">

    <div class="space-y-1">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ $title }}
        </h1>

        @if($description)
            <p class="module-card-sublabel">
                {{ $description }}
            </p>
        @endif
    </div>

    @if($buttonText && $buttonLink)
        <flux:button 
            variant="primary" 
            color="orange"
            icon="plus"
            href="{{ $buttonLink }}"
            class="rounded-xl px-5 py-2 text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200"
        >
            {{ $buttonText }}
        </flux:button>
    @endif

</x-module-card-lg>
