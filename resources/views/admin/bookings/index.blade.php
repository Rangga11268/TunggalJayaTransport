<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Management') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 w-full max-w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full max-w-full">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full max-w-full">
                <div class="p-4 sm:p-6 w-full max-w-full">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
                        <h3 class="text-lg sm:text-xl font-bold">Bookings</h3>
                        <a href="{{ route('admin.bookings.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center touch-friendly">
                            Add New Booking
                        </a>
                    </div>

                    <!-- Bookings Table -->
                    <div class="overflow-x-auto -mx-4 sm:mx-0 w-full max-w-full">
                        <table class="min-w-full divide-y divide-gray-200 booking-table w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passenger</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Bus</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Booking Status</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                        </td>
                                        <td class="px-4 py-3 sm:py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->passenger_name }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-[150px] sm:max-w-xs">{{ $booking->passenger_email }}</div>
                                        </td>
                                        <td class="px-4 py-3 sm:py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->schedule->route->name }}</div>
                                            <div class="text-sm text-gray-500 max-w-[120px] sm:max-w-xs truncate">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>
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
                                                    Pending
                                                </span>
                                            @elseif($booking->payment_status === 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Paid
                                                </span>
                                            @elseif($booking->payment_status === 'failed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Failed
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Refunded
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                            @if($booking->booking_status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($booking->booking_status === 'confirmed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Confirmed
                                                </span>
                                            @elseif($booking->booking_status === 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Completed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 action-buttons">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900 text-center touch-friendly text-sm">View</a>
                                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-900 text-center touch-friendly text-sm">Edit</a>
                                                <form id="delete-form-{{ $booking->id }}" action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="text-red-600 hover:text-red-900 w-full text-center touch-friendly text-sm" onclick="handleDelete('delete-form-{{ $booking->id }}', 'Hapus Pemesanan?', 'Apakah Anda yakin ingin menghapus pemesanan ini? Tindakan ini tidak dapat dibatalkan.')">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">
                                            No bookings found.
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