<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Pemesanan Baru
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Catatan:</strong> Jadwal yang sudah berangkat ditandai sebagai "BERANGKAT - Tidak Tersedia" dan tidak dapat dipilih.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.bookings.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Pengguna</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Pengguna</option>
                                    @foreach(App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="schedule_id" class="block text-sm font-medium text-gray-700">Jadwal</label>
                                <select name="schedule_id" id="schedule_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Jadwal</option>
                                    @foreach(App\Models\Schedule::with('route', 'bus')->where('status', 'active')->get() as $schedule)
                                        @if(!$schedule->hasDeparted())
                                        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->route->origin }} → {{ $schedule->route->destination }} ({{ $schedule->bus->name ?? 'Bus' }}) - {{ $schedule->getActualDepartureTime()->format('d M Y H:i') }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="text-sm text-gray-500 mt-1">
                                    Catatan: Jadwal yang sudah berangkat tidak tersedia untuk dipilih.
                                </div>
                                @error('schedule_id')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="passenger_name" class="block text-sm font-medium text-gray-700">Nama Penumpang</label>
                                <input type="text" name="passenger_name" id="passenger_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('passenger_name') }}" required>
                                @error('passenger_name')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="passenger_phone" class="block text-sm font-medium text-gray-700">Telepon Penumpang</label>
                                <input type="text" name="passenger_phone" id="passenger_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('passenger_phone') }}" required>
                                @error('passenger_phone')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="passenger_email" class="block text-sm font-medium text-gray-700">Email Penumpang</label>
                            <input type="email" name="passenger_email" id="passenger_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('passenger_email') }}" required>
                            @error('passenger_email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="seat_numbers" class="block text-sm font-medium text-gray-700">Nomor Kursi</label>
                                <input type="text" name="seat_numbers" id="seat_numbers" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('seat_numbers') }}" required>
                                @error('seat_numbers')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="total_price" class="block text-sm font-medium text-gray-700">Total Harga (Rp)</label>
                                <input type="number" name="total_price" id="total_price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('total_price') }}" required>
                                @error('total_price')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                                <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Tertunda</option>
                                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                                    <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                                </select>
                                @error('payment_status')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="booking_status" class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                                <select name="booking_status" id="booking_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="pending" {{ old('booking_status') == 'pending' ? 'selected' : '' }}>Tertunda</option>
                                    <option value="confirmed" {{ old('booking_status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                    <option value="cancelled" {{ old('booking_status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="completed" {{ old('booking_status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('booking_status')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800 touch-friendly">
                                ← Kembali ke Pemesanan
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded touch-friendly">
                                Tambah Pemesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>