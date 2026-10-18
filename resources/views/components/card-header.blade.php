@props([
    'title',
    'description' => null,
    'buttonText' => null,
    'buttonLink' => null,
    'buttonTextSecondary' => null,
    'buttonLinkSecondary' => null,
])

<x-module-card-lg class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="space-y-1">
        <h1 class="module-page-title">
            {{ $title }}
        </h1>

        @if($description)
            <p class="module-card-sublabel">
                {{ $description }}
            </p>
        @endif
    </div>

    @if(($buttonText && $buttonLink) || ($buttonTextSecondary && $buttonLinkSecondary))
        <div class="flex flex-wrap items-center gap-2">
            {{-- Acción secundaria: outline. Una sola acción primaria por vista. --}}
            @if($buttonTextSecondary && $buttonLinkSecondary)
                <flux:button variant="outline" :href="$buttonLinkSecondary">
                    {{ $buttonTextSecondary }}
                </flux:button>
            @endif

            {{-- Sin color="orange": toma --color-accent (orange-700 en light, orange-500 en dark) --}}
            @if($buttonText && $buttonLink)
                <flux:button variant="primary" icon="plus" :href="$buttonLink">
                    {{ $buttonText }}
                </flux:button>
            @endif
        </div>
    @endif
</x-module-card-lg>
