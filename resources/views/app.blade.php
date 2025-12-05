<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title inertia>{{ config('app.name', 'Tunggal Jaya Transport') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logoNoBg.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
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
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    @inertia
</body>
</html>
