<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;
use NotificationChannels\WebPush\Events\NotificationFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->addNamespace('layouts', resource_path('views/components/layouts'));
        Volt::mount([
            resource_path('views/livewire'),
            resource_path('views/pages'),
        ]);

        Event::listen(NotificationFailed::class, function (NotificationFailed $event) {
            Log::warning('No se pudo enviar la notificación push de la venta.', [
                'endpoint' => $event->report->getEndpoint(),
                'reason' => $event->report->getReason(),
                'expired' => $event->report->isSubscriptionExpired(),
            ]);
        });
    }
}
