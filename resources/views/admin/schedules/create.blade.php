<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Create New Schedule</h3>
                        <a href="{{ route('admin.schedules.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Back to Schedules
                        </a>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.schedules.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Bus -->
                            <div class="mb-4">
                                <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                                <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select a Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                            {{ $bus->name }} ({{ $bus->plate_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Route -->
                            <div class="mb-4">
                                <label for="route_id" class="block text-sm font-medium text-gray-700">Route</label>
                                <select name="route_id" id="route_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select a Route</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                            {{ $route->name }} ({{ $route->origin }} → {{ $route->destination }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('route_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Schedule Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Type</label>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <input type="radio" name="schedule_type" id="daily" value="daily" {{ old('schedule_type', 'daily') == 'daily' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                        <label for="daily" class="ml-3 block text-sm font-medium text-gray-700">One-time Schedule</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="schedule_type" id="weekly" value="weekly" {{ old('schedule_type') == 'weekly' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                        <label for="weekly" class="ml-3 block text-sm font-medium text-gray-700">Weekly Schedule</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="schedule_type" id="daily_recurring" value="daily_recurring" {{ old('schedule_type') == 'daily_recurring' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                        <label for="daily_recurring" class="ml-3 block text-sm font-medium text-gray-700">Daily Recurring</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Weekly Options -->
                            <div id="weekly-options" class="mb-4 {{ old('is_weekly', 0) == 1 ? '' : 'hidden' }}">
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700">Day of Week</label>
                                <select name="day_of_week" id="day_of_week" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select a Day</option>
                                    <option value="0" {{ old('day_of_week') == 0 ? 'selected' : '' }}>Sunday</option>
                                    <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Monday</option>
                                    <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Tuesday</option>
                                    <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Wednesday</option>
                                    <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Thursday</option>
                                    <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Friday</option>
                                    <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Saturday</option>
                                </select>
                                @error('day_of_week')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Daily Options -->
                            <div id="daily-options" class="mb-4 {{ (old('is_weekly', 0) == 0 && old('is_daily', 0) == 0) ? '' : 'hidden' }}">
                                <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date</label>
                                <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('departure_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Departure Time -->
                            <div class="mb-4">
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Arrival Time -->
                            <div class="mb-4">
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Arrival Time</label>
                                <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Price -->
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    min="0" step="0.01" required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.schedules.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scheduleType = document.querySelectorAll('input[name="schedule_type"]');
            const weeklyOptions = document.getElementById('weekly-options');
            const dailyOptions = document.getElementById('daily-options');
            
            scheduleType.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'weekly') {
                        // Weekly schedule
                        weeklyOptions.classList.remove('hidden');
                        dailyOptions.classList.add('hidden');
                    } else if (this.value === 'daily_recurring') {
                        // Daily recurring schedule
                        weeklyOptions.classList.add('hidden');
                        dailyOptions.classList.add('hidden');
                    } else {
                        // One-time schedule
                        weeklyOptions.classList.add('hidden');
                        dailyOptions.classList.remove('hidden');
                    }
                });
            });
            
            // Initialize based on checked radio
            const checkedRadio = document.querySelector('input[name="schedule_type"]:checked');
            
            if (checkedRadio) {
                if (checkedRadio.value === 'weekly') {
                    weeklyOptions.classList.remove('hidden');
                    dailyOptions.classList.add('hidden');
                } else if (checkedRadio.value === 'daily_recurring') {
                    weeklyOptions.classList.add('hidden');
                    dailyOptions.classList.add('hidden');
                } else {
                    weeklyOptions.classList.add('hidden');
                    dailyOptions.classList.remove('hidden');
                }
            } else {
                // Default to daily schedule
                dailyOptions.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>