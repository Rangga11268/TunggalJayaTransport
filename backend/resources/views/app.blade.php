<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Tunggal Jaya Transport') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logoNoBg.png') }}">

    <!-- PWA -->
    <meta name="theme-color" content="#e11d48">
    <link rel="manifest" href="/build/manifest.webmanifest">
    <link rel="apple-touch-icon" href="{{ asset('img/logoNoBg.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Unbounded:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
    <!-- Midtrans Snap -->
    <script type="text/javascript"
        src="https://app.{{ config('midtrans.environment') === 'sandbox' ? 'sandbox.midtrans.com' : 'midtrans.com' }}/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        #app-loader {
            position: fixed; inset: 0; display: flex; align-items: center; justify-content: center;
            background: #fcf9f8; z-index: 9999;
        }
        #app-loader::after {
            content: ''; width: 32px; height: 32px;
            border: 4px solid #e5e2e1; border-top-color: #10207a;
            border-radius: 50%; animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body class="font-sans antialiased bg-[#fcf9f8]">
    <div id="app-loader"></div>
    @inertia
    <script>document.getElementById('app-loader')&&requestAnimationFrame(()=>{let l=document.getElementById('app-loader');l&&(l.style.opacity='0',setTimeout(()=>l.remove(),200))})</script>
</body>

</html>
