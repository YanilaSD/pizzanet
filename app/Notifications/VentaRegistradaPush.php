<?php

namespace App\Notifications;

use App\Models\Venta;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class VentaRegistradaPush extends Notification
{
    public function __construct(public Venta $venta) {}

    /**
     * @return array<int, class-string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, Notification $notification): WebPushMessage
    {
        $puntos = (int) $this->venta->puntos;

        return (new WebPushMessage)
            ->title('Compra registrada')
            ->icon('/icons/icon-192.png')
            ->body("Registramos tu compra. Sumaste {$puntos} puntos.")
            ->data(['url' => '/cliente']);
    }
}
