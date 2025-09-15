@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Our Fleet</h1>
    
    <!-- Bus Gallery -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Bus Gallery</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($buses as $bus)
            <div class="border rounded-lg overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                @if($bus->getFirstMediaUrl('buses'))
                    <img src="{{ $bus->getFirstMediaUrl('buses') }}" alt="{{ $bus->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center">
                        <span class="text-gray-500">No Image Available</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-bold">{{ $bus->name ?? $bus->bus_type }}</h3>
                    <p class="text-gray-600">Capacity: {{ $bus->capacity }} seats</p>
                    <p class="text-gray-600">{{ $bus->description ?? 'No description available.' }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <p class="text-gray-600 text-center">No buses available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Our Drivers -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Our Professional Drivers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($drivers as $driver)
            <div class="border rounded-lg overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="bg-gray-200 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                    <i class="fas fa-user-circle text-8xl text-gray-400"></i>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold">{{ $driver->name }}</h3>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-id-card mr-2"></i>
                        License: {{ $driver->license_number }}
                    </p>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $driver->phone }}
                    </p>
                    @if($driver->email)
                    <p class="text-gray-600">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $driver->email }}
                    </p>
                    @endif
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <p class="text-gray-600 text-center">No drivers available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Facilities -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Facilities</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($facilities as $facility)
            <div class="text-center">
                @if($facility->icon)
                    <i class="{{ $facility->icon }} text-4xl text-blue-500 mx-auto mb-2"></i>
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                @endif
                <h3 class="text-lg font-bold">{{ $facility->name }}</h3>
                @if($facility->description)
                    <p class="text-gray-600 text-sm">{{ $facility->description }}</p>
                @endif
            </div>
            @empty
            <div class="col-span-4">
                <p class="text-gray-600 text-center">No facilities available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection