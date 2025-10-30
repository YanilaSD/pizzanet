<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->redirect(route('two-factor.login'), navigate: true);

            return;
        }

        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Pizzería Yuneth SRL" description="Ingresa tu correo y contraseña para iniciar sesion" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6" autocomplete="off">
        <!-- Email Address -->
        <flux:field>
            <flux:label class="text-white font-medium">
                {{ __('Correo') }}
            </flux:label>

            <flux:input
                wire:model="email"
                type="email"
                required
                autofocus
                autocomplete="email"
                :placeholder="__('mi_correo@pizzanet.com')"
                class="w-full"
                class:input="px-3 py-2"
            />

            <flux:error name="email" />
        </flux:field>

        <!-- Password -->
        <div class="relative">
            <flux:field>
                <flux:label class="text-white font-medium">
                    {{ __('Contraseña') }}
                </flux:label>

                <flux:input
                    wire:model="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Ingresa tu contraseña')"
                    viewable
                    class="w-full"
                    class:input="px-3 py-2"
                />

                <flux:error name="password" />
            </flux:field>


            @if (Route::has('password.request'))
                <!-- <flux:link class="absolute top-0 text-sm end-0 text-white" :href="route('password.request')" wire:navigate>
                    {{ __('Olvidaste tu contraseña?') }}
                </flux:link> -->
            @endif
        </div>

        <!-- Remember Me -->
        <flux:field variant="inline">
            <flux:checkbox wire:model="remember" id="remember" />
            <flux:label for="remember" class="text-white">
                {{ __('Recuerdame') }}
            </flux:label>
        </flux:field>


        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full bg-orange-500" data-test="login-button">
                {{ __('Iniciar sesión') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <!-- <div class="space-x-1 text-sm text-center text-white rtl:space-x-reverse  dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link class="text-white" :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div> -->
    @endif
</div>
