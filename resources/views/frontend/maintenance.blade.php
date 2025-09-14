<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Tunggal Jaya Transport') }} - Maintenance</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mx-auto bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mb-6"></div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">We'll Be Back Soon</h1>
            <p class="text-gray-600 mb-6">
                We're currently performing scheduled maintenance to improve your experience. We apologize for any inconvenience and appreciate your patience.
            </p>
            <div class="text-sm text-gray-500">
                <p>Expected to be back online in: <span class="font-medium">30 minutes</span></p>
                <p class="mt-2">Thank you for your understanding.</p>
            </div>
        </div>
    </div>
</body>
</html>