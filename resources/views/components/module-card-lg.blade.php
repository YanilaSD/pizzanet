@props([
    'title' => null,
    'description' => null
])
<div {{ $attributes->class(['module-card-lg']) }}>
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

    @if($title || $description)
        <p class="border-b border-zinc-200 dark:border-zinc-800 my-2 mb-4" />
    @endif

    {{ $slot }}
</div>
