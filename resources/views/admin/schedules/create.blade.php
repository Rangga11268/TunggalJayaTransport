<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Jadwal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Buat Jadwal Baru</h3>
                        <a href="{{ route('admin.schedules.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Kembali ke Jadwal
                        </a>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.schedules.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Bus -->
                            <div class="mb-4">
                                <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                                <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Bus</option>
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
                                <label for="route_id" class="block text-sm font-medium text-gray-700">Rute</label>
                                <select name="route_id" id="route_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Rute</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Jadwal</label>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <input type="radio" name="schedule_type" id="daily" value="daily" {{ old('schedule_type', 'daily') == 'daily' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                        <label for="daily" class="ml-3 block text-sm font-medium text-gray-700">Jadwal Satu Kali</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="schedule_type" id="daily_recurring" value="daily_recurring" {{ old('schedule_type') == 'daily_recurring' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                        <label for="daily_recurring" class="ml-3 block text-sm font-medium text-gray-700">Jadwal Harian Berulang</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Daily Options -->
                            <div id="daily-options" class="mb-4 {{ (old('is_weekly', 0) == 0 && old('is_daily', 0) == 0) ? '' : 'hidden' }}">
                                <label for="departure_date" class="block text-sm font-medium text-gray-700">Tanggal Keberangkatan</label>
                                <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('departure_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Departure Time -->
                            <div class="mb-4">
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Waktu Keberangkatan</label>
                                <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Arrival Time -->
                            <div class="mb-4">
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Waktu Kedatangan</label>
                                <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Price -->
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
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
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>Tertunda</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.schedules.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buat Jadwal
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
            const dailyOptions = document.getElementById('daily-options');
            
            scheduleType.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'daily_recurring') {
                        // Daily recurring schedule - hide date input
                        dailyOptions.classList.add('hidden');
                    } else {
                        // One-time schedule - show date input
                        dailyOptions.classList.remove('hidden');
                    }
                });
            });
            
            // Initialize based on checked radio
            const checkedRadio = document.querySelector('input[name="schedule_type"]:checked');
            
            if (checkedRadio) {
                if (checkedRadio.value === 'daily_recurring') {
                    dailyOptions.classList.add('hidden');
                } else {
                    dailyOptions.classList.remove('hidden');
                }
            } else {
                // Default to daily schedule
                dailyOptions.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>