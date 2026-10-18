<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
    <flux:navlist.item 
        :href="route('profile.edit')" 
        class="!text-gray-700 dark:!text-white hover:!text-white {{ request()->routeIs('profile.edit') ? '!bg-orange-800 !text-white' : '' }}"
        wire:navigate
    >
        {{ __('Profile') }}
    </flux:navlist.item>

    <flux:navlist.item 
        :href="route('password.edit')" 
        class="!text-gray-700 dark:!text-white hover:!text-white {{ request()->routeIs('password.edit') ? '!bg-orange-800 !text-white' : '' }}"
        wire:navigate
    >
        {{ __('Password') }}
    </flux:navlist.item>

    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <flux:navlist.item 
            :href="route('two-factor.show')" 
            class="!text-gray-700 dark:!text-white hover:!text-white {{ request()->routeIs('two-factor.show') ? '!bg-orange-800 !text-white' : '' }}"
            wire:navigate
        >
            {{ __('Two-Factor Auth') }}
        </flux:navlist.item>
    @endif

    <flux:navlist.item 
        :href="route('appearance.edit')" 
        class="!text-gray-700 dark:!text-white hover:!text-white {{ request()->routeIs('appearance.edit') ? '!bg-orange-800 !text-white' : '' }}"
        wire:navigate
    >
        {{ __('Appearance') }}
    </flux:navlist.item>
</flux:navlist>


    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
