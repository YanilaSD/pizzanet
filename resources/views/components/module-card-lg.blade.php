@props([
    'title' => null,
    'description' => null
])
<div {{ $attributes->class(['module-card-lg']) }}>
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

    @if($title || $description)
        <p class="border-b border-gray-200 dark:border-gray-700 my-2 mb-4" />
    @endif

    {{ $slot }}
</div>
