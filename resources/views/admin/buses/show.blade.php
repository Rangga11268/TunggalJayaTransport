<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Bus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h1 class="text-2xl font-bold">{{ $bus->name }}</h1>
                                <div class="mt-2">
                                    @if($bus->status === 'active')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @elseif($bus->status === 'maintenance')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Maintenance
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                                <a href="{{ route('admin.buses.edit', $bus) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center touch-friendly">
                                    Edit
                                </a>
                                <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full touch-friendly" onclick="return confirm('Are you sure you want to delete this bus?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Details</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Plate Number:</dt>
                                    <dd class="text-gray-900">{{ $bus->plate_number }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Bus Type:</dt>
                                    <dd class="text-gray-900">{{ $bus->bus_type }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Capacity:</dt>
                                    <dd class="text-gray-900">{{ $bus->capacity }} seats</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Status:</dt>
                                    <dd class="text-gray-900">
                                        @if($bus->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @elseif($bus->status === 'maintenance')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Maintenance
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium mb-4">Description</h3>
                            @if($bus->description)
                                <p class="text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $bus->description }}</p>
                            @else
                                <p class="text-gray-500 italic bg-gray-50 p-4 rounded-lg">No description provided.</p>
                            @endif
                            
                            @if($bus->getFirstMediaUrl('buses'))
                                <div class="mt-4">
                                    <h3 class="text-lg font-medium mb-2">Bus Image</h3>
                                    <img src="{{ $bus->getFirstMediaUrl('buses') }}" alt="Bus Image" class="w-full max-w-md h-auto rounded-lg shadow">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Drivers Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium mb-4">Assigned Drivers</h3>
                        @if($bus->drivers->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($bus->drivers as $driver)
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                                        <div class="font-medium">{{ $driver->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $driver->license_number }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic bg-gray-50 p-4 rounded-lg">No drivers assigned to this bus.</p>
                        @endif
                    </div>
                    
                    <!-- Conductors Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium mb-4">Assigned Conductors</h3>
                        @if($bus->conductors->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($bus->conductors as $conductor)
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                                        <div class="font-medium">{{ $conductor->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $conductor->employee_id }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic bg-gray-50 p-4 rounded-lg">No conductors assigned to this bus.</p>
                        @endif
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ route('admin.buses.index') }}" class="text-gray-600 hover:text-gray-800 touch-friendly">
                            ← Back to Buses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>