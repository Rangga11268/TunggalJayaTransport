<div class="flex" x-data="{
    sidebarOpen: true,
    init() {
        // Auto-collapse sidebar on small screens
        if (window.innerWidth < 768) {
            this.sidebarOpen = false;
        }
        
        // Listen for window resize events
        window.addEventListener('resize', () => {
            if (window.innerWidth < 768) {
                this.sidebarOpen = false;
            } else {
                this.sidebarOpen = true;
            }
        });
    }
}">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 min-h-screen transition-all duration-300 relative">
        <div class="p-4 flex items-center justify-between">
            <h1 :class="sidebarOpen ? 'block' : 'hidden'" class="text-white text-xl font-bold whitespace-nowrap">Panel Admin</h1>
            <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
                <i :class="sidebarOpen ? 'fas fa-angle-left' : 'fas fa-angle-right'" class="text-xl"></i>
            </button>
        </div>
        <nav class="mt-5">
            <a href="{{ route('admin.dashboard') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-tachometer-alt text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Dashboard</span>
                </div>
            </a>
            <a href="{{ route('admin.news.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.news.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-newspaper text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Berita</span>
                </div>
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-tags text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Kategori</span>
                </div>
            </a>
            <a href="{{ route('admin.buses.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.buses.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-bus text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Bus</span>
                </div>
            </a>
            <a href="{{ route('admin.routes.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.routes.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-route text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Rute</span>
                </div>
            </a>
            <a href="{{ route('admin.schedules.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.schedules.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Jadwal</span>
                </div>
            </a>
            <a href="{{ route('admin.bookings.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-ticket-alt text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Pemesanan</span>
                </div>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-users text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Pengguna</span>
                </div>
            </a>
            <a href="{{ route('admin.facilities.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.facilities.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-concierge-bell text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Fasilitas</span>
                </div>
            </a>
            <a href="{{ route('admin.drivers.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.drivers.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-id-card text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Manajemen Driver</span>
                </div>
            </a>
            <a href="{{ route('admin.reports.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-chart-bar text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Laporan</span>
                </div>
            </a>
            <a href="{{ route('admin.settings.index') }}"
                class="block py-3 px-4 text-white hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-900' : '' }} transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fas fa-cog text-lg w-6"></i> 
                    <span :class="sidebarOpen ? 'ml-2 inline' : 'hidden'">Pengaturan</span>
                </div>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div :class="sidebarOpen ? 'flex-1' : 'flex-1'" class="flex-1">
        <!-- Top Navigation -->
        <nav class="bg-white shadow" x-data="{ open: false }" @click.outside="open = false">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Situs Utama') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
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
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profil') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Keluar') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger Menu for Mobile -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('frontend.home')" :active="request()->routeIs('frontend.home')">
                        {{ __('Situs Utama') }}
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</div>

<!-- Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
