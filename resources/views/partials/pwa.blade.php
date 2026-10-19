<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#f97316">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Yuneth">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(@json(asset('sw.js')));
    }
</script>
