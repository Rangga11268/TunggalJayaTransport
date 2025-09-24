<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tunggal Jaya Transport') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('img/logoNoBg.png') }}">

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

        <!-- Rellax.js for parallax effect -->
        <script src="https://cdn.jsdelivr.net/npm/rellax@1.12.1/rellax.min.js"></script>

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
                
                // Initialize Rellax for parallax effect
                if (typeof Rellax !== 'undefined') {
                    new Rellax('[data-rellax-speed]', {
                        speed: -2,
                        center: false,
                        wrapper: null,
                        round: true,
                        vertical: true,
                        horizontal: false
                    });
                }
            });
            
            // Quick Booking Form Alpine.js component
            function quickBookingForm() {
                return {
                    origin: '',
                    destination: '',
                    date: '',
                    busType: '',
                    origins: [],
                    destinations: [],
                    filteredOrigins: [],
                    filteredDestinations: [],
                    busTypes: [
                        { id: 'all', name: 'All Bus Types' },
                        { id: 'economy', name: 'Economy' },
                        { id: 'business', name: 'Business' },
                        { id: 'executive', name: 'Executive' }
                    ],
                    selectedBusType: 'all',
                    availableSeats: null,
                    originDropdownOpen: false,
                    destinationDropdownOpen: false,
                    originHighlightedIndex: -1,
                    destinationHighlightedIndex: -1,
                    isFiltering: false,
                    init() {
                        // Check if we're on the home page and have inline data
                        if (typeof window.homepageData !== 'undefined') {
                            this.origins = window.homepageData.origins || [];
                            this.destinations = window.homepageData.destinations || [];
                            this.filteredOrigins = this.origins;
                            this.filteredDestinations = this.destinations;
                        } else {
                            // Fetch origins and destinations from server
                            fetch('{{ route("frontend.autocomplete.data") }}')
                                .then(response => response.json())
                                .then(data => {
                                    this.origins = data.origins;
                                    this.destinations = data.destinations;
                                    this.filteredOrigins = data.origins;
                                    this.filteredDestinations = data.destinations;
                                })
                                .catch(error => {
                                    console.error('Error fetching autocomplete data:', error);
                                });
                        }
                    },
                    checkAvailability() {
                        // Check if all required fields are filled
                        if (this.origin && this.destination && this.date) {
                            // Make an actual AJAX request to check availability
                            fetch('{{ route("frontend.check-availability") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    origin: this.origin,
                                    destination: this.destination,
                                    date: this.date,
                                    bus_type: this.selectedBusType
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    this.availableSeats = data.available_seats || 0;
                                } else {
                                    this.availableSeats = 0;
                                }
                            })
                            .catch(error => {
                                console.error('Error checking availability:', error);
                                this.availableSeats = 0;
                            });
                        } else {
                            this.availableSeats = null;
                        }
                    },
                    filterOrigins() {
                        this.isFiltering = true;
                        setTimeout(() => {
                            if (this.origin === '') {
                                this.filteredOrigins = this.origins;
                                this.originDropdownOpen = false;
                                this.originHighlightedIndex = -1;
                            } else {
                                this.filteredOrigins = this.origins.filter(o => 
                                    o.toLowerCase().includes(this.origin.toLowerCase())
                                );
                                this.originDropdownOpen = true;
                                this.originHighlightedIndex = -1;
                            }
                            this.isFiltering = false;
                        }, 100);
                    },
                    filterDestinations() {
                        this.isFiltering = true;
                        setTimeout(() => {
                            if (this.destination === '') {
                                this.filteredDestinations = this.destinations;
                                this.destinationDropdownOpen = false;
                                this.destinationHighlightedIndex = -1;
                            } else {
                                this.filteredDestinations = this.destinations.filter(d => 
                                    d.toLowerCase().includes(this.destination.toLowerCase())
                                );
                                this.destinationDropdownOpen = true;
                                this.destinationHighlightedIndex = -1;
                            }
                            this.isFiltering = false;
                        }, 100);
                    },
                    selectOrigin(value) {
                        this.origin = value;
                        this.filteredOrigins = this.origins;
                        this.originDropdownOpen = false;
                        this.originHighlightedIndex = -1;
                        this.checkAvailability();
                    },
                    selectDestination(value) {
                        this.destination = value;
                        this.filteredDestinations = this.destinations;
                        this.destinationDropdownOpen = false;
                        this.destinationHighlightedIndex = -1;
                        this.checkAvailability();
                    },
                    closeOriginDropdown() {
                        // Only close if not interacting with dropdown items
                        setTimeout(() => {
                            if (this.originHighlightedIndex === -1) {
                                this.originDropdownOpen = false;
                                this.originHighlightedIndex = -1;
                            }
                        }, 150);
                    },
                    closeDestinationDropdown() {
                        setTimeout(() => {
                            if (this.destinationHighlightedIndex === -1) {
                                this.destinationDropdownOpen = false;
                                this.destinationHighlightedIndex = -1;
                            }
                        }, 150);
                    },
                    highlightOrigin(index) {
                        this.originHighlightedIndex = index;
                    },
                    highlightDestination(index) {
                        this.destinationHighlightedIndex = index;
                    },
                    handleOriginKeydown(event) {
                        if (!this.originDropdownOpen || this.filteredOrigins.length === 0) return;
                        
                        switch(event.key) {
                            case 'ArrowDown':
                                event.preventDefault();
                                this.originHighlightedIndex = (this.originHighlightedIndex + 1) % this.filteredOrigins.length;
                                break;
                            case 'ArrowUp':
                                event.preventDefault();
                                this.originHighlightedIndex = this.originHighlightedIndex <= 0 ? 
                                    this.filteredOrigins.length - 1 : this.originHighlightedIndex - 1;
                                break;
                            case 'Enter':
                                event.preventDefault();
                                if (this.originHighlightedIndex >= 0 && this.originHighlightedIndex < this.filteredOrigins.length) {
                                    this.selectOrigin(this.filteredOrigins[this.originHighlightedIndex]);
                                }
                                break;
                            case 'Escape':
                                this.originDropdownOpen = false;
                                this.originHighlightedIndex = -1;
                                break;
                        }
                    },
                    handleDestinationKeydown(event) {
                        if (!this.destinationDropdownOpen || this.filteredDestinations.length === 0) return;
                        
                        switch(event.key) {
                            case 'ArrowDown':
                                event.preventDefault();
                                this.destinationHighlightedIndex = (this.destinationHighlightedIndex + 1) % this.filteredDestinations.length;
                                break;
                            case 'ArrowUp':
                                event.preventDefault();
                                this.destinationHighlightedIndex = this.destinationHighlightedIndex <= 0 ? 
                                    this.filteredDestinations.length - 1 : this.destinationHighlightedIndex - 1;
                                break;
                            case 'Enter':
                                event.preventDefault();
                                if (this.destinationHighlightedIndex >= 0 && this.destinationHighlightedIndex < this.filteredDestinations.length) {
                                    this.selectDestination(this.filteredDestinations[this.destinationHighlightedIndex]);
                                }
                                break;
                            case 'Escape':
                                this.destinationDropdownOpen = false;
                                this.destinationHighlightedIndex = -1;
                                break;
                        }
                    }
                }
            }
            
            // Function to get user's location for route recommendations
            function getUserLocation() {
                return {
                    userLocation: null,
                    init() {
                        // Try to get the user's location
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                (position) => {
                                    this.userLocation = {
                                        latitude: position.coords.latitude,
                                        longitude: position.coords.longitude
                                    };
                                    // You can use this location data to get route recommendations
                                    console.log('User location:', this.userLocation);
                                },
                                (error) => {
                                    console.log('Error getting location:', error.message);
                                    // Use fallback location or popular routes if location permission is denied
                                },
                                {
                                    enableHighAccuracy: true,
                                    timeout: 10000,
                                    maximumAge: 300000 // 5 minutes
                                }
                            );
                        } else {
                            console.log('Geolocation is not supported by this browser.');
                        }
                    }
                };
            }
        </script>
        
        @yield('scripts')
    </body>
</html>