<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    window.pizzaColors = [
        '#FF6A00', // naranja pizza
        '#FF8C42', 
        '#FF4500',
        '#FFA500',
        '#E63946'
    ];

    window.Apex = {
        grid: {
            padding: { top: 10, right: 12, bottom: 10, left: 12 }
        },
        theme: {
            mode: 'light',
            palette: 'palette1',
            monochrome: { enabled: false }
        }
    };
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

