<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.buses.update', $bus) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Bus Name</label>
                                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('name', $bus->name) }}" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="plate_number" class="block text-sm font-medium text-gray-700">Plate Number</label>
                                    <input type="text" name="plate_number" id="plate_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('plate_number', $bus->plate_number) }}" required>
                                    @error('plate_number')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="bus_type" class="block text-sm font-medium text-gray-700">Bus Type</label>
                                    <input type="text" name="bus_type" id="bus_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('bus_type', $bus->bus_type) }}" required>
                                    @error('bus_type')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                    <input type="number" name="capacity" id="capacity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('capacity', $bus->capacity) }}" required>
                                    @error('capacity')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly">{{ old('description', $bus->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" required>
                                        <option value="active" {{ old('status', $bus->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="maintenance" {{ old('status', $bus->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="inactive" {{ old('status', $bus->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700">Bus Image</label>
                                    <input type="file" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly">
                                    @if($bus->getFirstMediaUrl('buses'))
                                        <div class="mt-2">
                                            <img src="{{ $bus->getFirstMediaUrl('buses') }}" alt="Current Bus Image" class="w-32 h-32 object-cover rounded">
                                        </div>
                                    @endif
                                    @error('image')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Drivers Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Drivers</label>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($drivers as $driver)
                                    <div class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50">
                                        <input type="checkbox" name="drivers[]" value="{{ $driver->id }}" id="driver_{{ $driver->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" {{ in_array($driver->id, old('drivers', $bus->drivers->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label for="driver_{{ $driver->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $driver->name }} ({{ $driver->license_number }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('drivers')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Conductors Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Conductors</label>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($conductors as $conductor)
                                    <div class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50">
                                        <input type="checkbox" name="conductors[]" value="{{ $conductor->id }}" id="conductor_{{ $conductor->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" {{ in_array($conductor->id, old('conductors', $bus->conductors->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label for="conductor_{{ $conductor->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $conductor->name }} ({{ $conductor->employee_id }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('conductors')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
                            <a href="{{ route('admin.buses.index') }}" class="text-gray-600 hover:text-gray-800 w-full sm:w-auto text-center touch-friendly">
                                ← Back to Buses
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto touch-friendly">
                                Update Bus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>