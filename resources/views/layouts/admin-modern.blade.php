<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for interactivity -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
    
    <style>
        :root {
            --primary: #007bff;
            --secondary: #6c757d;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 56px;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            box-shadow: 0 0 1rem rgba(0, 0, 0, 0.15);
            z-index: 1030;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .main-panel {
            transition: all 0.3s ease;
            margin-left: var(--sidebar-width);
        }
        
        .sidebar.collapsed ~ .main-panel {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .topbar {
            height: var(--topbar-height);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            z-index: 1020;
        }
        
        .sidebar-nav .nav-link {
            border-radius: 0.375rem;
            margin: 0.25rem 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        
        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        .sidebar-brand {
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .content-wrapper {
            padding: 1.5rem;
            flex: 1;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.375rem;
        }
        
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1rem 1.25rem;
            background-color: rgba(0, 0, 0, 0.03);
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 500;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
            color: #fff;
        }
        
        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }
        
        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
            color: #212529;
        }
        
        .btn-info {
            background-color: var(--info);
            border-color: var(--info);
            color: #fff;
        }
        
        .stat-card {
            border-left: 4px solid var(--primary);
        }
        
        .stat-card.success {
            border-left-color: var(--success);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning);
        }
        
        .stat-card.danger {
            border-left-color: var(--danger);
        }
        
        .stat-card.info {
            border-left-color: var(--info);
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-panel {
                margin-left: 0;
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1020;
                display: none;
            }
            
            .overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="wrapper flex h-screen" 
         x-data="{ 
             sidebarOpen: window.innerWidth >= 992,
             mobileSidebarOpen: false,
             toggleSidebar() {
                 if (window.innerWidth >= 992) {
                     this.sidebarOpen = !this.sidebarOpen;
                 } else {
                     this.mobileSidebarOpen = !this.mobileSidebarOpen;
                 }
             }
         }">
        
        <!-- Overlay for mobile -->
        <div class="overlay" 
             x-show="mobileSidebarOpen" 
             @click="mobileSidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>
        
        <!-- Sidebar -->
        <aside class="sidebar bg-gradient-to-b from-gray-800 to-gray-900 text-white flex flex-col h-full fixed lg:relative"
               :class="{ 'collapsed': !sidebarOpen && window.innerWidth >= 992, 'show': mobileSidebarOpen }"
               x-transition:enter="transition-transform ease-out duration-300"
               x-transition:enter-start="transform -translate-x-full"
               x-transition:enter-end="transform translate-x-0"
               x-transition:leave="transition-transform ease-in duration-300"
               x-transition:leave-start="transform translate-x-0"
               x-transition:leave-end="transform -translate-x-full">
            
            <div class="sidebar-brand">
                <div class="flex items-center">
                    <i class="fas fa-bus text-xl mr-2"></i>
                    <span x-show="sidebarOpen || window.innerWidth < 992" class="font-bold">Tunggal Jaya</span>
                </div>
            </div>
            
            <div class="sidebar-nav flex-1 overflow-y-auto py-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}" 
                           href="{{ route('admin.news.index') }}">
                            <i class="fas fa-newspaper"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">News Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                           href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-tags"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Category Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}" 
                           href="{{ route('admin.buses.index') }}">
                            <i class="fas fa-bus"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Bus Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}" 
                           href="{{ route('admin.routes.index') }}">
                            <i class="fas fa-route"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Route Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" 
                           href="{{ route('admin.schedules.index') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Schedule Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" 
                           href="{{ route('admin.bookings.index') }}">
                            <i class="fas fa-ticket-alt"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Booking Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                           href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}" 
                           href="{{ route('admin.facilities.index') }}">
                            <i class="fas fa-concierge-bell"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Facility Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}" 
                           href="{{ route('admin.drivers.index') }}">
                            <i class="fas fa-id-card"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Driver Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.conductors.*') ? 'active' : '' }}" 
                           href="{{ route('admin.conductors.index') }}">
                            <i class="fas fa-user-tie"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Conductor Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                           href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                           href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-cog"></i>
                            <span x-show="sidebarOpen || window.innerWidth < 992">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="p-3 border-t border-gray-700 text-center text-sm">
                <div x-show="sidebarOpen || window.innerWidth < 992">
                    <p>Admin Panel v1.0</p>
                    <p class="text-gray-400">© {{ date('Y') }} Tunggal Jaya</p>
                </div>
            </div>
        </aside>
        
        <!-- Main Panel -->
        <div class="main-panel flex flex-col flex-1 min-h-0">
            <!-- Topbar -->
            <nav class="topbar bg-white flex items-center justify-between px-4">
                <div class="flex items-center">
                    <button @click="toggleSidebar" class="text-gray-600 mr-3">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">
                        @isset($header)
                            {{ $header }}
                        @else
                            Admin Dashboard
                        @endisset
                    </h1>
                </div>
                
                <div class="flex items-center">
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <div class="mr-2">{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                             x-transition:enter="transition-all ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition-all ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Content -->
            <div class="content-wrapper bg-gray-100 overflow-y-auto flex-1">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>