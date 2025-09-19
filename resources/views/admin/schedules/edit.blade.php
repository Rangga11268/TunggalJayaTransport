<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($schedule->hasDeparted())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        <strong>Warning:</strong> This schedule has already departed. 
                                        Editing this schedule may affect existing bookings.
                                        <br><br>
                                        <strong>Note:</strong> For departed schedules, only bookings that are pending payment will be automatically cancelled by the system.
                                        Confirmed and paid bookings remain valid.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                            <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Select a bus</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}" {{ old('bus_id', $schedule->bus_id) == $bus->id ? 'selected' : '' }}>
                                        {{ $bus->name }} ({{ $bus->plate_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bus_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="route_id" class="block text-sm font-medium text-gray-700">Route</label>
                            <select name="route_id" id="route_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Select a route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
                                        {{ $route->name }} ({{ $route->origin }} → {{ $route->destination }})
                                    </option>
                                @endforeach
                            </select>
                            @error('route_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Schedule Type</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" name="is_weekly" id="daily" value="0" {{ old('is_weekly', $schedule->is_weekly ? 1 : 0) == 0 ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <label for="daily" class="ml-3 block text-sm font-medium text-gray-700">Daily Schedule</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="is_weekly" id="weekly" value="1" {{ old('is_weekly', $schedule->is_weekly ? 1 : 0) == 1 ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <label for="weekly" class="ml-3 block text-sm font-medium text-gray-700">Weekly Schedule</label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="weekly-options" class="mb-4 {{ $schedule->is_weekly ? '' : 'hidden' }}">
                            <label for="day_of_week" class="block text-sm font-medium text-gray-700">Day of Week</label>
                            <select name="day_of_week" id="day_of_week" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a day</option>
                                <option value="0" {{ old('day_of_week', $schedule->day_of_week) == 0 ? 'selected' : '' }}>Sunday</option>
                                <option value="1" {{ old('day_of_week', $schedule->day_of_week) == 1 ? 'selected' : '' }}>Monday</option>
                                <option value="2" {{ old('day_of_week', $schedule->day_of_week) == 2 ? 'selected' : '' }}>Tuesday</option>
                                <option value="3" {{ old('day_of_week', $schedule->day_of_week) == 3 ? 'selected' : '' }}>Wednesday</option>
                                <option value="4" {{ old('day_of_week', $schedule->day_of_week) == 4 ? 'selected' : '' }}>Thursday</option>
                                <option value="5" {{ old('day_of_week', $schedule->day_of_week) == 5 ? 'selected' : '' }}>Friday</option>
                                <option value="6" {{ old('day_of_week', $schedule->day_of_week) == 6 ? 'selected' : '' }}>Saturday</option>
                            </select>
                            @error('day_of_week')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="daily-options" class="mb-4 {{ $schedule->is_weekly ? 'hidden' : '' }}">
                            <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date</label>
                            <input type="date" name="departure_date" id="departure_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('departure_date', $schedule->departure_time ? $schedule->departure_time->format('Y-m-d') : '') }}">
                            @error('departure_date')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <input type="time" name="departure_time" id="departure_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('departure_time', $schedule->departure_time ? $schedule->departure_time->format('H:i') : '') }}" required>
                                @error('departure_time')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Arrival Time</label>
                                <input type="time" name="arrival_time" id="arrival_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('arrival_time', $schedule->arrival_time ? $schedule->arrival_time->format('H:i') : '') }}" required>
                                @error('arrival_time')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                            <input type="number" name="price" id="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('price', $schedule->price) }}" required>
                            @error('price')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="active" {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="delayed" {{ old('status', $schedule->status) == 'delayed' ? 'selected' : '' }}>Delayed</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.schedules.index') }}" class="text-gray-600 hover:text-gray-800">
                                ← Back to Schedules
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleType = document.querySelectorAll('input[name="is_weekly"]');
        const weeklyOptions = document.getElementById('weekly-options');
        const dailyOptions = document.getElementById('daily-options');
        
        scheduleType.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value == '1') {
                    weeklyOptions.classList.remove('hidden');
                    dailyOptions.classList.add('hidden');
                } else {
                    weeklyOptions.classList.add('hidden');
                    dailyOptions.classList.remove('hidden');
                }
            });
        });
        
        // Initialize based on current value
        const checkedRadio = document.querySelector('input[name="is_weekly"]:checked');
        if (checkedRadio && checkedRadio.value == '1') {
            weeklyOptions.classList.remove('hidden');
            dailyOptions.classList.add('hidden');
        }
    });
    </script>
</x-app-layout>