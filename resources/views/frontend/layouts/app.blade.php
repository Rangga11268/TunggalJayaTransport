<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tunggal Jaya Transport') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Swiper.js CSS -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

        <!-- Custom Loading Screen Styles -->
        <style>
            #loading-screen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #1e40af, #4f46e5);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.5s ease-out;
            }
            
            .loading-logo {
                width: 120px;
                height: 120px;
                margin-bottom: 30px;
                animation: pulse 2s infinite;
            }
            
            .loading-spinner {
                width: 50px;
                height: 50px;
                border: 5px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top-color: white;
                animation: spin 1s ease-in-out infinite;
                margin-bottom: 30px;
            }
            
            .loading-text {
                color: white;
                font-size: 1.2rem;
                font-weight: 500;
                letter-spacing: 1px;
                animation: fadeInOut 1.5s infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            @keyframes fadeInOut {
                0%, 100% { opacity: 0.5; }
                50% { opacity: 1; }
            }
            
            .loading-hidden {
                opacity: 0;
                pointer-events: none;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Custom Loading Screen -->
        <div id="loading-screen">
            <img src="{{ asset('img/logoNoBg.png') }}" alt="Tunggal Jaya Transport" class="loading-logo">
            <div class="loading-spinner"></div>
            <div class="loading-text">Preparing your journey...</div>
        </div>

        <!-- Header -->
        @include('frontend.partials.header')

        <!-- Page Content -->
        <main class="pt-16">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('frontend.partials.footer')

        <!-- Swiper.js -->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        <!-- Alpine.js for interactivity -->
        <script src="//unpkg.com/alpinejs" defer></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Loading Screen Script -->
        <script>
            window.addEventListener('load', function() {
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.classList.add('loading-hidden');
                
                // Remove the loading screen after the fade-out transition
                setTimeout(function() {
                    loadingScreen.style.display = 'none';
                }, 500);
            });
        </script>
        
        @yield('scripts')
    </body>
</html>