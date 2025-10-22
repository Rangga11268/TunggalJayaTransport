<header 
    id="navbar"
    class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white shadow-lg transition-all duration-300 ease-in-out fixed w-full z-50"
    style="background-color: rgba(29, 78, 216, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);"
    x-data="{ 
        open: false
    }" 
    @click.outside="open = false"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 navbar-container navbar-max-width">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('frontend.home') }}">
                        <img src="{{ asset('img/logoNoBg.png') }}" alt="Tunggal Jaya Transport Logo" class="block h-12 w-auto navbar-logo">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 md:space-x-8 md:-my-px md:ms-6 md:flex navbar-nav-links">
                    <x-nav-link :href="route('frontend.home')" :active="request()->routeIs('frontend.home')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.booking.index')" :active="request()->routeIs('frontend.booking.*')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('Booking') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.routes.index')" :active="request()->routeIs('frontend.routes.*')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('Routes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.fleet.index')" :active="request()->routeIs('frontend.fleet.*')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('Fleet') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.news.index')" :active="request()->routeIs('frontend.news.*')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('News') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.about')" :active="request()->routeIs('frontend.about')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('About') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.contact')" :active="request()->routeIs('frontend.contact')" class="text-white hover:text-blue-200 whitespace-nowrap text-sm md:text-base">
                        {{ __('Contact') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ms-4 navbar-dropdown">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-2 py-1 md:px-3 md:py-2 border border-transparent text-xs md:text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="truncate max-w-[80px] md:max-w-[120px]">{{ Auth::user()->name ?? 'Guest' }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-3 w-3 md:h-4 md:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @auth
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('schedule_manager'))
                                <x-dropdown-link :href="route('dashboard')" class="hover:bg-blue-50">
                                    {{ __('Admin Dashboard') }}
                                </x-dropdown-link>
                            @else
                                <!-- Regular User Profile Link -->
                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-blue-50">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                            @endif
                            
                            <!-- User Features -->
                            <x-dropdown-link :href="route('booking-history.index')" class="hover:bg-blue-50">
                                {{ __('Booking History') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="hover:bg-blue-50">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        @else
                            <x-dropdown-link :href="route('login')" class="hover:bg-blue-50">
                                {{ __('Login') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('register')" class="hover:bg-blue-50">
                                {{ __('Register') }}
                            </x-dropdown-link>
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-200 hover:bg-blue-600 focus:outline-none focus:bg-blue-600 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-blue-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('frontend.home')" :active="request()->routeIs('frontend.home')" class="text-white hover:bg-blue-700">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.booking.index')" :active="request()->routeIs('frontend.booking.*')" class="text-white hover:bg-blue-700">
                {{ __('Booking') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.routes.index')" :active="request()->routeIs('frontend.routes.*')" class="text-white hover:bg-blue-700">
                {{ __('Routes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.fleet.index')" :active="request()->routeIs('frontend.fleet.*')" class="text-white hover:bg-blue-700">
                {{ __('Fleet') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.news.index')" :active="request()->routeIs('frontend.news.*')" class="text-white hover:bg-blue-700">
                {{ __('News') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.about')" :active="request()->routeIs('frontend.about')" class="text-white hover:bg-blue-700">
                {{ __('About') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.contact')" :active="request()->routeIs('frontend.contact')" class="text-white hover:bg-blue-700">
                {{ __('Contact') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name ?? 'Guest' }}</div>
                <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @auth
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('schedule_manager'))
                        <x-responsive-nav-link :href="route('dashboard')" class="text-white hover:bg-blue-700">
                            {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                    @endif
                    
                    <!-- User Features -->
                    <x-responsive-nav-link :href="route('booking-history.index')" class="text-white hover:bg-blue-700">
                        {{ __('Booking History') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-white hover:bg-blue-700">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')" class="text-white hover:bg-blue-700">
                        {{ __('Login') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')" class="text-white hover:bg-blue-700">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
    
    <!-- Responsive navbar fixes -->
    <style>
        /* Mobile to tablet fixes (640px to 768px) */
        @media (min-width: 640px) and (max-width: 768px) {
            /* Reduce the space between navigation items */
            #navbar .space-x-6 {
                gap: 0.5rem;
            }
            
            /* Reduce left margin of navigation section */
            #navbar .md\:ms-6 {
                margin-left: 0.5rem;
            }
            
            /* Adjust right margin of dropdown */
            #navbar .md\:ms-4 {
                margin-left: 0.25rem;
            }
            
            /* Reduce container padding */
            #navbar .px-4 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Adjust max width container */
            #navbar .max-w-7xl {
                max-width: 100%;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Reduce font size of navigation links */
            #navbar .md\:flex a {
                font-size: 0.75rem;
                padding-left: 0.3rem;
                padding-right: 0.3rem;
            }
            
            /* Reduce padding on dropdown button */
            #navbar button.inline-flex {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }
            
            /* Adjust logo size */
            #navbar .h-12 {
                height: 2.25rem;
            }
            
            /* Ensure dropdown doesn't overlap with navigation items */
            #navbar .relative {
                position: relative;
                z-index: 60;
            }
            
            /* Add flex shrink to navigation links to prevent overflow */
            #navbar .md\:flex {
                flex-shrink: 1;
            }
            
            /* Ensure navigation container can shrink if needed */
            #navbar .flex {
                min-width: 0;
            }
            
            /* Limit user name width */
            #navbar .truncate {
                max-width: 70px;
            }
        }
        
        /* Tablet-specific navbar fixes (768px to 1024px) */
        @media (min-width: 768px) and (max-width: 1024px) {
            /* Reduce the space between navigation items on tablet to prevent overflow */
            #navbar .space-x-8 {
                gap: 0.75rem;
            }
            
            /* Reduce left margin of navigation section on tablet */
            #navbar .md\:ms-6 {
                margin-left: 0.75rem;
            }
            
            /* Adjust right margin of dropdown on tablet */
            #navbar .md\:ms-4 {
                margin-left: 0.5rem;
            }
            
            /* Reduce container padding on tablet */
            #navbar .px-4 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            /* Adjust max width container on tablet */
            #navbar .max-w-7xl {
                max-width: 100%;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            /* Reduce font size of navigation links on tablet */
            #navbar .md\:flex a {
                font-size: 0.8rem;
                padding-left: 0.4rem;
                padding-right: 0.4rem;
            }
            
            /* Reduce padding on dropdown button for tablet */
            #navbar button.inline-flex {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }
            
            /* Adjust logo size on tablet */
            #navbar .h-12 {
                height: 2.5rem;
            }
            
            /* Ensure dropdown doesn't overlap with navigation items */
            #navbar .relative {
                position: relative;
                z-index: 60;
            }
            
            /* Add flex shrink to navigation links to prevent overflow */
            #navbar .md\:flex {
                flex-shrink: 1;
            }
            
            /* Ensure navigation container can shrink if needed */
            #navbar .flex {
                min-width: 0;
            }
            
            /* Limit user name width */
            #navbar .truncate {
                max-width: 100px;
            }
        }
        
        /* Additional fixes for larger tablets (iPad Pro, etc.) */
        @media (min-width: 1024px) and (max-width: 1200px) {
            #navbar .space-x-8 {
                gap: 1rem;
            }
            
            #navbar .md\:flex a {
                font-size: 0.85rem;
            }
        }
        
        /* Ensure proper z-index for dropdown */
        #navbar .relative {
            z-index: 60;
        }
        
        /* Prevent text wrapping in nav links */
        #navbar .whitespace-nowrap {
            white-space: nowrap;
        }
        
        /* Fix for small mobile screens */
        @media (max-width: 639px) {
            #navbar .h-16 {
                height: 4rem;
            }
            
            #navbar .px-4 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
    </style>
</header>