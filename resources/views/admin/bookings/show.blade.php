<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lihat Pemesanan
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold">Detail Pemesanan</h1>
                        <div class="mt-2 text-sm text-gray-500 flex items-center">
                            <span>Kode Pemesanan: {{ $booking->booking_code }}</span>
                            @if($booking->schedule->hasDeparted())
                                <span class="ml-3 bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    JADWAL BERANGKAT
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-4">Informasi Penumpang</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Nama:</dt>
                                    <dd class="text-gray-900">{{ $booking->passenger_name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Telepon:</dt>
                                    <dd class="text-gray-900">{{ $booking->passenger_phone }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Email:</dt>
                                    <dd class="text-gray-900">{{ $booking->passenger_email }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Nomor Kursi:</dt>
                                    <dd class="text-gray-900">{{ $booking->seat_numbers }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium mb-4">Informasi Pemesanan</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Total Harga:</dt>
                                    <dd class="text-gray-900">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Status Pembayaran:</dt>
                                    <dd class="text-gray-900">
                                        @if($booking->payment_status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Tertunda
                                            </span>
                                        @elseif($booking->payment_status === 'paid')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Dibayar
                                            </span>
                                        @elseif($booking->payment_status === 'failed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Gagal
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Dikembalikan
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Status Pemesanan:</dt>
                                    <dd class="text-gray-900">
                                        @if($booking->booking_status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Tertunda
                                            </span>
                                        @elseif($booking->booking_status === 'confirmed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Dikonfirmasi
                                            </span>
                                        @elseif($booking->booking_status === 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Selesai
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-medium mb-4">Informasi Jadwal</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Rute:</dt>
                                <dd class="text-gray-900">{{ $booking->schedule->route->name }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Asal:</dt>
                                <dd class="text-gray-900">{{ $booking->schedule->route->origin }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Tujuan:</dt>
                                <dd class="text-gray-900">{{ $booking->schedule->route->destination }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Bus:</dt>
                                <dd class="text-gray-900">{{ $booking->schedule->bus->name }} ({{ $booking->schedule->bus->plate_number }})</dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Keberangkatan:</dt>
                                <dd class="text-gray-900">
                                    {{ $booking->schedule->getActualDepartureTime()->format('d M Y H:i') }}
                                    @if($booking->schedule->hasDeparted())
                                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
                                            BERANGKAT
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Kedatangan:</dt>
                                <dd class="text-gray-900">{{ $booking->schedule->getActualArrivalTime()->format('d M Y H:i') }}</dd>
                            </div>
                            @if($booking->schedule->hasDeparted())
                                <div class="flex md:col-span-2">
                                    <dt class="font-medium text-gray-500 w-32">Status:</dt>
                                    <dd class="text-red-600 font-semibold">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Jadwal ini sudah berangkat. Pemesanan tidak lagi valid.
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800 touch-friendly">
                            ← Kembali ke Pemesanan
                        </a>
                        <div>
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 touch-friendly">
                                Edit
                            </a>
                            <form id="delete-form" action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded touch-friendly" onclick="handleDelete('delete-form', 'Hapus Pemesanan?', 'Apakah Anda yakin ingin menghapus pemesanan ini? Tindakan ini tidak dapat dibatalkan.')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>