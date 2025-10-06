<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Jadwal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Jadwal #{{ $schedule->id }}</h3>
                        <a href="{{ route('admin.schedule-management.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali ke Jadwal
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Schedule Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-bold mb-4 text-gray-800">Informasi Jadwal</h4>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bus:</span>
                                    <span class="font-medium">{{ $schedule->bus->name ?? 'N/A' }} ({{ $schedule->bus->plate_number ?? 'N/A' }})</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rute:</span>
                                    <span class="font-medium">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipe Jadwal:</span>
                                    <span class="font-medium">
                                        @if($schedule->is_daily)
                                            Jadwal Harian Berulang
                                        @else
                                            Jadwal Harian
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu Keberangkatan:</span>
                                    <span class="font-medium">{{ $schedule->getDepartureTimeWIB()->format('d M Y H:i') }} (WIB)</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu Kedatangan:</span>
                                    <span class="font-medium">{{ $schedule->getArrivalTimeWIB()->format('d M Y H:i') }} (WIB)</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Harga:</span>
                                    <span class="font-medium">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">
                                        @if($schedule->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        @elseif($schedule->status === 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Tertunda
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sudah Berangkat:</span>
                                    <span class="font-medium">
                                        @if($hasDeparted)
                                            <span class="text-red-600 font-bold">YA</span>
                                        @else
                                            <span class="text-green-600 font-bold">TIDAK</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tersedia untuk Pemesanan:</span>
                                    <span class="font-medium">
                                        @if($isAvailable)
                                            <span class="text-green-600 font-bold">YA</span>
                                        @else
                                            <span class="text-red-600 font-bold">TIDAK</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Capacity Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-bold mb-4 text-gray-800">Informasi Kapasitas</h4>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kapasitas Bus:</span>
                                    <span class="font-medium">{{ $schedule->bus->capacity ?? 0 }} kursi</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kursi Dipesan:</span>
                                    <span class="font-medium">{{ $bookedSeats }} kursi</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kursi Tersedia:</span>
                                    <span class="font-medium">{{ $availableSeats }} kursi</span>
                                </div>
                                
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-blue-600 h-4 rounded-full" 
                                         style="width: {{ ($availableSeats / max(1, $schedule->bus->capacity ?? 1)) * 100 }}%">
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-500 mt-2">
                                    {{ round(($availableSeats / max(1, $schedule->bus->capacity ?? 1)) * 100, 1) }}% tersedia
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bookings Section -->
                    <div class="mt-8">
                        <h4 class="text-md font-bold mb-4 text-gray-800">Pemesanan ({{ $schedule->bookings->count() }})</h4>
                        
                        @if($schedule->bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Pemesanan</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penumpang</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kursi</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedule->bookings as $booking)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $booking->booking_code }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $booking->passenger_name }}</div>
                                            <div class="text-xs">{{ $booking->passenger_email }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->number_of_seats }} kursi
                                            @if($booking->seat_numbers)
                                            <div class="text-xs">({{ $booking->seat_numbers }})</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($booking->booking_status === 'confirmed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Dikonfirmasi
                                                </span>
                                            @elseif($booking->booking_status === 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ ucfirst(__($booking->booking_status)) }}
                                                </span>
                                            @endif
                                            
                                            @if($booking->payment_status === 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ml-1">
                                                    Dibayar
                                                </span>
                                            @elseif($booking->payment_status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-1">
                                                    Tertunda
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 ml-1">
                                                    {{ ucfirst(__($booking->payment_status)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4 text-gray-500">
                            Tidak ada pemesanan ditemukan untuk jadwal ini.
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Jadwal
                        </a>
                        
                        @if($schedule->hasDeparted() && $schedule->is_daily)
                            <form action="{{ route('admin.schedules.create-next-day', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Buat Jadwal Hari Berikutnya
                                </button>
                            </form>
                        @endif
                        
                        <form id="delete-form" action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                    onclick="event.preventDefault(); handleDelete('delete-form', 'Hapus Jadwal?', 'Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.')">
                                Hapus Jadwal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>