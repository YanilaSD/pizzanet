@props([
    'action',
    'placeholder',
    'value' => '',
    'name' => 'search',
])

<form method="GET" action="{{ $action }}" class="module-index-search">
    <div class="min-w-0 w-full flex-1">
        <flux:input
            :name="$name"
            icon="magnifying-glass"
            :placeholder="$placeholder"
            :value="$value"
            class="w-full"
        />
    </div>

    <flux:button
        type="submit"
        variant="primary"
        class="module-header-primary w-full shrink-0 sm:w-auto"
    >
        {{ __('Search') }}
    </flux:button>
</form>
