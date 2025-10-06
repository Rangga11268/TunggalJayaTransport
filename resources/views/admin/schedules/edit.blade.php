<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Jadwal
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
                                        <strong>Peringatan:</strong> Jadwal ini sudah berangkat. 
                                        Mengedit jadwal ini mungkin memengaruhi pemesanan yang sudah ada.
                                        <br><br>
                                        <strong>Catatan:</strong> Untuk jadwal yang sudah berangkat, hanya pemesanan yang menunggu pembayaran yang akan otomatis dibatalkan oleh sistem.
                                        Pemesanan yang sudah dikonfirmasi dan dibayar tetap valid.
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
                                <option value="">Pilih bus</option>
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
                            <label for="route_id" class="block text-sm font-medium text-gray-700">Rute</label>
                            <select name="route_id" id="route_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Pilih rute</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Jadwal</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="radio" name="schedule_type" id="daily" value="daily" {{ old('schedule_type', $schedule->is_daily ? 'daily_recurring' : 'daily') == 'daily' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <label for="daily" class="ml-3 block text-sm font-medium text-gray-700">Jadwal Satu Kali</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="schedule_type" id="daily_recurring" value="daily_recurring" {{ old('schedule_type', $schedule->is_daily ? 'daily_recurring' : 'daily') == 'daily_recurring' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <label for="daily_recurring" class="ml-3 block text-sm font-medium text-gray-700">Jadwal Harian Berulang</label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="daily-options" class="mb-4 {{ (!$schedule->is_daily && old('is_daily', $schedule->is_daily ? 1 : 0) == 0) ? '' : 'hidden' }}">
                            <label for="departure_date" class="block text-sm font-medium text-gray-700">Tanggal Keberangkatan</label>
                            <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date', $schedule->is_daily ? '' : $schedule->departure_time->format('Y-m-d')) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('departure_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Waktu Keberangkatan</label>
                                <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time', $schedule->getDepartureTimeWIB()->format('H:i')) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Waktu Kedatangan</label>
                                <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time', $schedule->getArrivalTimeWIB()->format('H:i')) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $schedule->price) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    min="0" step="0.01" required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="active" {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="delayed" {{ old('status', $schedule->status) == 'delayed' ? 'selected' : '' }}>Tertunda</option>
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
                                Perbarui Jadwal
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
                // Default based on schedule properties
                if ('{{ $schedule->is_daily ? 'true' : 'false' }}' === 'true') {
                    dailyOptions.classList.add('hidden');
                } else {
                    dailyOptions.classList.remove('hidden');
                }
            }
        });
    </script>
</x-app-layout>