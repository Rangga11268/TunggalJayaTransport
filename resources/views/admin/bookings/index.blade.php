<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pemesanan
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 w-full max-w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full max-w-full">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full max-w-full">
                <div class="p-4 sm:p-6 w-full max-w-full">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
                        <h3 class="text-lg sm:text-xl font-bold">Pemesanan</h3>
                        <a href="{{ route('admin.bookings.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center touch-friendly">
                            Tambah Pemesanan Baru
                        </a>
                    </div>

                    <!-- Bookings Table -->
                    <div class="overflow-x-auto -mx-4 sm:mx-0 w-full max-w-full">
                        <table class="min-w-full divide-y divide-gray-200 booking-table w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Pemesanan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penumpang</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pemesanan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Tanggal Keberangkatan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Bus</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Status Pemesanan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bookings as $booking)
                                    <tr class="{{ $booking->schedule->hasDeparted() ? 'bg-red-50' : '' }}">
                                        <td class="px-4 py-3 sm:py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                            @if($booking->schedule->hasDeparted())
                                                <div class="text-xs text-red-600 font-semibold">BERANGKAT</div>
                                            @endif
                                            @if($booking->schedule->hasDeparted() && $booking->payment_status == 'pending')
                                                <div class="text-xs text-orange-600 font-semibold">AKAN DIBATALKAN</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 sm:py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->passenger_name }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-[150px] sm:max-w-xs">{{ $booking->passenger_email }}</div>
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                            {{ $booking->schedule->getActualDepartureTime()->format('d M Y H:i') }}
                                            @if($booking->schedule->hasDeparted())
                                                <span class="text-red-600 font-semibold">(Berangkat)</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 sm:py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                            {{ $booking->schedule->bus->name }}
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp. {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap">
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
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
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
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-row gap-2 action-buttons">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="view-icon" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="edit-icon" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form id="delete-form-{{ $booking->id }}" action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-icon" onclick="handleDelete('delete-form-{{ $booking->id }}', 'Hapus Pemesanan?', 'Apakah Anda yakin ingin menghapus pemesanan ini? Tindakan ini tidak dapat dibatalkan.')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Tidak ada pemesanan ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 sm:mt-6">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>