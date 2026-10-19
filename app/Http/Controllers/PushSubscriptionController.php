<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Minishlink\WebPush\ContentEncoding;

class PushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $cliente = Cliente::find(session('portal_cliente_id'));

        if (! $cliente || $cliente->esAnonimo()) {
            abort(403);
        }

        $data = $request->validate([
            'endpoint' => ['required', 'string', 'max:1024'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
            'contentEncoding' => ['nullable', 'string', 'in:aes128gcm,aesgcm'],
        ]);

        $cliente->updatePushSubscription(
            $data['endpoint'],
            $data['keys']['p256dh'],
            $data['keys']['auth'],
            $data['contentEncoding'] ?? ContentEncoding::aes128gcm->value,
        );

        return response()->json(['ok' => true]);
    }
}
