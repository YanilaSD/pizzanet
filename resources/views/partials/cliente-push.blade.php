<div class="space-y-2">
    <button
        type="button"
        id="activar-notificaciones"
        class="w-full rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-600 disabled:cursor-default disabled:opacity-70"
    >
        Activar notificaciones
    </button>
    <p id="notificaciones-estado" class="text-sm text-zinc-600 dark:text-zinc-300"></p>
</div>
<script>
    (function () {
        var button = document.getElementById('activar-notificaciones');
        var status = document.getElementById('notificaciones-estado');
        var publicKey = @json(config('webpush.vapid.public_key'));
        var subscribeUrl = @json(route('cliente.suscripcion'));
        var token = document.querySelector('meta[name="csrf-token"]');

        function setStatus(message) {
            status.textContent = message;
        }

        function urlBase64ToUint8Array(base64String) {
            var padding = '='.repeat((4 - (base64String.length % 4)) % 4);
            var base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            var raw = window.atob(base64);
            var output = new Uint8Array(raw.length);

            for (var i = 0; i < raw.length; i++) {
                output[i] = raw.charCodeAt(i);
            }

            return output;
        }

        function markActive() {
            button.disabled = true;
            button.textContent = 'Notificaciones activas';
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then(function (registration) {
                return registration.pushManager.getSubscription();
            }).then(function (subscription) {
                if (subscription) {
                    markActive();
                }
            }).catch(function () {});
        }

        button.addEventListener('click', function () {
            if (!publicKey) {
                setStatus('Las notificaciones no están configuradas.');
                return;
            }

            if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                setStatus('Este navegador no admite notificaciones push.');
                return;
            }

            Notification.requestPermission().then(function (permission) {
                if (permission !== 'granted') {
                    setStatus('Activa el permiso de notificaciones en el navegador.');
                    return;
                }

                return navigator.serviceWorker.ready.then(function (registration) {
                    var encodings = (window.PushManager && PushManager.supportedContentEncodings) || ['aes128gcm'];

                    return registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(publicKey),
                    }).then(function (subscription) {
                        var payload = subscription.toJSON();
                        payload.contentEncoding = encodings[0] || 'aes128gcm';

                        return fetch(subscribeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token ? token.getAttribute('content') : '',
                            },
                            body: JSON.stringify(payload),
                        });
                    });
                }).then(function (response) {
                    if (!response || !response.ok) {
                        setStatus('No se pudo guardar la suscripción.');
                        return;
                    }

                    markActive();
                    setStatus('Te avisaremos cuando registremos una compra.');
                }).catch(function () {
                    setStatus('No se pudo activar las notificaciones.');
                });
            });
        });
    })();
</script>
