<div class="flex min-h-screen w-full max-w-full"
     :class="{
        'flex-col': window.innerWidth < 1024 && sidebarOpen,
        'flex-row': window.innerWidth >= 1024 || !sidebarOpen
     }"
     x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
     x-init="() => {
        const handleResize = () => {
            if (window.innerWidth >= 1024) {
                sidebarOpen = true;
            } else {
                sidebarOpen = false;
            }
        };
        
        handleResize();
        
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleResize, 250);
        });
     }">
    <!-- Sidebar - Hidden on mobile by default -->
    <div :class="{
            'w-64 lg:block lg:relative': sidebarOpen && window.innerWidth >= 1024, 
            'w-20 lg:block lg:relative': !sidebarOpen && window.innerWidth >= 1024,
            'w-64 fixed z-30 block mobile-sidebar-fixed': sidebarOpen && window.innerWidth < 1024,
            'w-0 hidden': !sidebarOpen && window.innerWidth < 1024
         }" 
         class="bg-gradient-to-b from-gray-800 to-gray-900 text-white min-h-screen shadow-xl flex-shrink-0 flex flex-col sidebar mobile-sidebar-container">
        <div class="p-4 flex items-center justify-between border-b border-gray-700 flex-shrink-0">
            <div :class="{
                    'block': sidebarOpen, 
                    'hidden': !sidebarOpen
                }" 
                class="transition-opacity duration-300 flex items-center">
                <img src="{{ asset('img/logoNoBg.png') }}" alt="Tunggal Jaya Transport Logo" class="h-10 w-auto mr-2">
                <h1 class="text-xl font-bold whitespace-nowrap">Dasbor Admin</h1>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="text-white focus:outline-none hover:bg-gray-700 rounded-full p-2 transition-colors duration-200">
                <i :class="sidebarOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
            </button>
        </div>
        <!-- Scrollable sidebar content -->
        <div class="overflow-y-auto flex-1 mobile-sidebar-scrollable">
            <nav class="mt-5 px-2 pb-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    <i class="fas fa-tachometer-alt text-lg w-6"></i> 
                    <span :class="{
                            'ml-3 inline': sidebarOpen, 
                            'hidden': !sidebarOpen
                        }" 
                        class="transition-opacity duration-300">Dasbor</span>
                </a>

                <!-- Content Management Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }" class="mb-1">
                    <button @click="open = !open" 
                        class="flex items-center justify-between w-full p-3 rounded-lg transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.categories.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-lg w-6"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300">Manajemen Konten</span>
                        </div>
                        <i :class="{
                                'fa-chevron-down': open,
                                'fa-chevron-right': !open
                            }" 
                            :class="{
                                'inline': sidebarOpen, 
                                'hidden': !sidebarOpen
                            }"
                            class="fas transition-transform duration-200 text-sm"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="pl-4">
                        <a href="{{ route('admin.news.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.news.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-newspaper text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Berita</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-tags text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Kategori</span>
                        </a>
                    </div>
                </div>

                <!-- Transport Management Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.buses.*') || request()->routeIs('admin.routes.*') || request()->routeIs('admin.schedules.*') || request()->routeIs('admin.schedule-management.*') ? 'true' : 'false' }} }" class="mb-1">
                    <button @click="open = !open" 
                        class="flex items-center justify-between w-full p-3 rounded-lg transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.buses.*') || request()->routeIs('admin.routes.*') || request()->routeIs('admin.schedules.*') || request()->routeIs('admin.schedule-management.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-week text-lg w-6"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 font-medium">Manajemen Transportasi</span>
                        </div>
                        <i :class="{
                                'fa-chevron-down': open,
                                'fa-chevron-right': !open
                            }" 
                            :class="{
                                'inline': sidebarOpen, 
                                'hidden': !sidebarOpen
                            }"
                            class="fas transition-transform duration-200 text-sm"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="pl-4">
                        <a href="{{ route('admin.buses.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.buses.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-bus text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Manajemen Bus</span>
                        </a>
                        <a href="{{ route('admin.routes.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.routes.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-route text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Manajemen Rute</span>
                        </a>
                        <a href="{{ route('admin.schedules.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.schedules.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-calendar-alt text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Manajemen Jadwal</span>
                        </a>
                        <a href="{{ route('admin.schedule-management.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.schedule-management.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-tasks text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Dasbor Jadwal</span>
                        </a>
                    </div>
                </div>

                <!-- Booking Management -->
                <a href="{{ route('admin.bookings.index') }}"
                    class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    <i class="fas fa-ticket-alt text-lg w-6"></i> 
                    <span :class="{
                            'ml-3 inline': sidebarOpen, 
                            'hidden': !sidebarOpen
                        }" 
                        class="transition-opacity duration-300">Manajemen Pemesanan</span>
                </a>

                <!-- User Management Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.drivers.*') || request()->routeIs('admin.conductors.*') ? 'true' : 'false' }} }" class="mb-1">
                    <button @click="open = !open" 
                        class="flex items-center justify-between w-full p-3 rounded-lg transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.drivers.*') || request()->routeIs('admin.conductors.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-users text-lg w-6"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300">Manajemen Pengguna</span>
                        </div>
                        <i :class="{
                                'fa-chevron-down': open,
                                'fa-chevron-right': !open
                            }" 
                            :class="{
                                'inline': sidebarOpen, 
                                'hidden': !sidebarOpen
                            }"
                            class="fas transition-transform duration-200 text-sm"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="pl-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-user text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Pengguna</span>
                        </a>
                        <a href="{{ route('admin.drivers.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.drivers.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-id-card text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Driver</span>
                        </a>
                        <a href="{{ route('admin.conductors.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.conductors.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-user-tie text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Conductor</span>
                        </a>
                    </div>
                </div>

                <!-- Facility Management -->
                <a href="{{ route('admin.facilities.index') }}"
                    class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.facilities.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    <i class="fas fa-concierge-bell text-lg w-6"></i> 
                    <span :class="{
                            'ml-3 inline': sidebarOpen, 
                            'hidden': !sidebarOpen
                        }" 
                        class="transition-opacity duration-300">Manajemen Fasilitas</span>
                </a>

                <!-- Reporting -->
                <a href="{{ route('admin.reports.index') }}"
                    class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    <i class="fas fa-chart-bar text-lg w-6"></i> 
                    <span :class="{
                            'ml-3 inline': sidebarOpen, 
                            'hidden': !sidebarOpen
                        }" 
                        class="transition-opacity duration-300">Laporan</span>
                </a>

                <!-- System Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }" class="mb-1">
                    <button @click="open = !open" 
                        class="flex items-center justify-between w-full p-3 rounded-lg transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-cog text-lg w-6"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300">Sistem</span>
                        </div>
                        <i :class="{
                                'fa-chevron-down': open,
                                'fa-chevron-right': !open
                            }" 
                            :class="{
                                'inline': sidebarOpen, 
                                'hidden': !sidebarOpen
                            }"
                            class="fas transition-transform duration-200 text-sm"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="pl-4">
                        <a href="{{ route('admin.settings.index') }}"
                            class="flex items-center p-3 rounded-lg mb-1 transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-cog text-base w-4"></i> 
                            <span :class="{
                                    'ml-3 inline': sidebarOpen, 
                                    'hidden': !sidebarOpen
                                }" 
                                class="transition-opacity duration-300 text-sm ml-4">Pengaturan</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
         x-cloak></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-0 w-full max-w-full overflow-x-hidden"
         :class="{
            'lg:ml-0': window.innerWidth >= 1024,
            'ml-0': window.innerWidth < 1024
         }">
        <!-- Top Navigation -->
        <nav class="bg-white shadow-sm z-10 sticky top-0 flex-shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Single hamburger menu button for both sidebar and mobile menu -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="text-gray-500 hover:text-gray-700 focus:outline-none mr-2 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Breadcrumb or page title -->
                        <div class="text-lg font-semibold text-gray-800 truncate">
                            @isset($header)
                                {{ $header }}
                            @else
                                Admin Panel
                            @endisset
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                                    <div class="mr-2">{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-gray-100">
                                    {{ __('Profil') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                        class="hover:bg-gray-100">
                                        {{ __('Keluar') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6 w-full max-w-full">
            {{ $slot }}
        </main>
    </div>
</div>

<!-- Pass success messages to JavaScript -->
@if(session('create_success'))
    <div data-create-success-message="{{ session('create_success') }}" style="display: none;"></div>
@endif

@if(session('success'))
    <div data-success-message="{{ session('success') }}" style="display: none;"></div>
@endif

@if(session('delete_success'))
    <div data-delete-success-message="{{ session('delete_success') }}" style="display: none;"></div>
@endif

@if(session('update_success'))
    <div data-update-success-message="{{ session('update_success') }}" style="display: none;"></div>
@endif

<!-- Yield scripts section -->
@yield('scripts')
