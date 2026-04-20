<div class="flex items-center justify-between bg-white/80 backdrop-blur-sm border border-gray-100 p-6 rounded-2xl shadow-sm">

    <div class="space-y-1">
        <h1 class="text-xl font-semibold text-gray-900">
            {{ $title }}
        </h1>

        @if($description)
            <p class="text-sm text-gray-500">
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

</div>
