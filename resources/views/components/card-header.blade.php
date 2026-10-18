@props([
    'title',
    'description' => null,
    'buttonText' => null,
    'buttonLink' => null,
    'buttonTextSecondary' => null,
    'buttonLinkSecondary' => null,
])

<x-module-card-lg class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

    <div class="min-w-0 space-y-1">
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
        <div class="flex w-full min-w-0 flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-stretch lg:w-auto lg:justify-end">
            {{-- Acción secundaria: outline. Una sola acción primaria por vista. --}}
            @if($buttonTextSecondary && $buttonLinkSecondary)
                <flux:button variant="outline" :href="$buttonLinkSecondary" class="w-full justify-center sm:w-auto">
                    {{ $buttonTextSecondary }}
                </flux:button>
            @endif

            {{-- Light: accent orange-700. Dark: .module-header-primary (estilo sidebar) --}}
            @if($buttonText && $buttonLink)
                <flux:button
                    variant="primary"
                    icon="plus"
                    :href="$buttonLink"
                    class="module-header-primary w-full justify-center sm:w-auto"
                >
                    {{ $buttonText }}
                </flux:button>
            @endif
        </div>
    @endif
</x-module-card-lg>
