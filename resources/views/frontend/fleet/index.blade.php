@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Our Fleet</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Experience comfortable and safe journeys with our modern fleet of buses and professional drivers</p>
    </div>
    
    <!-- Bus Gallery -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Bus Gallery</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-bus mr-1"></i>{{ $buses->count() }} Buses
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($buses as $bus)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                @if($bus->getFirstMediaUrl('buses'))
                    <div class="relative">
                        <img src="{{ $bus->getFirstMediaUrl('buses') }}" alt="{{ $bus->name }}" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                            {{ $bus->bus_type ?? 'Standard' }}
                        </div>
                    </div>
                @else
                    <div class="relative bg-gradient-to-r from-gray-200 to-gray-300 w-full h-56 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-bus text-gray-500 text-5xl mb-2"></i>
                            <p class="text-gray-500 font-medium">No Image Available</p>
                        </div>
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                            {{ $bus->bus_type ?? 'Standard' }}
                        </div>
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-xl font-bold text-gray-800">{{ $bus->name ?? $bus->bus_type }}</h3>
                        <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-chair mr-1"></i>{{ $bus->capacity }} seats
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $bus->description ?? 'No description available for this bus.' }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-gas-pump mr-1"></i>Fuel: {{ $bus->fuel_type ?? 'Diesel' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-calendar-alt mr-1"></i>Year: {{ $bus->year ?? 'N/A' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-hashtag mr-1"></i>{{ $bus->plate_number ?? 'No Plate' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="fas fa-bus"></i>
                    </div>
                    <p class="text-gray-600 text-lg">No buses available at the moment.</p>
                    <p class="text-gray-500 mt-2">Please check back later for updates.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Our Drivers -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Our Professional Drivers</h2>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-user-tie mr-1"></i>{{ $drivers->count() }} Drivers
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($drivers as $driver)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative">
                    <div class="bg-gradient-to-r from-gray-200 to-gray-300 w-full h-56 flex items-center justify-center">
                        <i class="fas fa-user-circle text-8xl text-gray-400"></i>
                    </div>
                    <div class="absolute top-4 right-4 bg-green-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                        <i class="fas fa-id-card mr-1"></i>ID: {{ $driver->id }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $driver->name }}</h3>
                    <div class="space-y-3">
                        <p class="text-gray-600 flex items-center">
                            <i class="fas fa-id-card text-green-500 mr-3 w-5"></i>
                            <span class="font-medium">License:</span> 
                            <span class="ml-2">{{ $driver->license_number }}</span>
                        </p>
                        <p class="text-gray-600 flex items-center">
                            <i class="fas fa-phone text-green-500 mr-3 w-5"></i>
                            <span class="font-medium">Phone:</span> 
                            <span class="ml-2">{{ $driver->phone }}</span>
                        </p>
                        @if($driver->email)
                        <p class="text-gray-600 flex items-center">
                            <i class="fas fa-envelope text-green-500 mr-3 w-5"></i>
                            <span class="font-medium">Email:</span> 
                            <span class="ml-2">{{ $driver->email }}</span>
                        </p>
                        @endif
                        <div class="pt-3 border-t border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active Driver
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <p class="text-gray-600 text-lg">No drivers available at the moment.</p>
                    <p class="text-gray-500 mt-2">Please check back later for updates.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Facilities -->
    <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Facilities</h2>
            <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-concierge-bell mr-1"></i>{{ $facilities->count() }} Facilities
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($facilities as $facility)
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300 text-center transform hover:-translate-y-1">
                @if($facility->icon)
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="{{ $facility->icon }} text-purple-600 text-2xl"></i>
                    </div>
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-concierge-bell text-gray-500 text-2xl"></i>
                    </div>
                @endif
                <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $facility->name }}</h3>
                @if($facility->description)
                    <p class="text-gray-600 text-sm">{{ $facility->description }}</p>
                @endif
            </div>
            @empty
            <div class="col-span-4">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <p class="text-gray-600 text-lg">No facilities available at the moment.</p>
                    <p class="text-gray-500 mt-2">Please check back later for updates.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection