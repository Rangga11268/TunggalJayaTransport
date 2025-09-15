<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight responsive-padding">
            {{ __('Booking Management') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mobile-card">
                <div class="p-4 md:p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mobile-card-header">Bookings</h3>
                        <a href="{{ route('admin.bookings.create') }}" class="w-full md:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transform transition duration-300 hover:scale-105 text-center btn-enhanced touch-target mobile-mb-4">
                            <i class="fas fa-plus-circle mr-2"></i> Add New Booking
                        </a>
                    </div>

                    <!-- Bookings Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 table-responsive-mobile">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passenger</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Bus</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Booking Status</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bookings as $booking)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap" data-label="Booking Code">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                        </td>
                                        <td class="px-4 md:px-6 py-4" data-label="Passenger">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->passenger_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->passenger_email }}</div>
                                        </td>
                                        <td class="px-4 md:px-6 py-4" data-label="Route">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->schedule->route->name }}</div>
                                            <div class="text-sm text-gray-500 hidden md:block">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>
                                            <div class="text-sm text-gray-500 md:hidden">{{ substr($booking->schedule->route->origin, 0, 10) }} → {{ substr($booking->schedule->route->destination, 0, 10) }}</div>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell" data-label="Bus">
                                            {{ $booking->schedule->bus->name }}
                                        </td>
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-label="Price">
                                            Rp. {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap" data-label="Payment Status">
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
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap hidden md:table-cell" data-label="Booking Status">
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
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium" data-label="Actions">
                                            <div class="flex space-x-2 mobile-table-actions">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 btn-icon touch-target" title="View">
                                                    <i class="fas fa-eye icon-mobile-medium md:icon-tablet-medium"></i>
                                                </a>
                                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-blue-600 hover:text-blue-900 btn-icon touch-target" title="Edit">
                                                    <i class="fas fa-edit icon-mobile-medium md:icon-tablet-medium"></i>
                                                </a>
                                                <form id="delete-form-{{ $booking->id }}" action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="text-red-600 hover:text-red-900 btn-icon touch-target" onclick="handleDelete('delete-form-{{ $booking->id }}', 'Hapus Pemesanan?', 'Apakah Anda yakin ingin menghapus pemesanan ini? Tindakan ini tidak dapat dibatalkan.')" title="Delete">
                                                        <i class="fas fa-trash icon-mobile-medium md:icon-tablet-medium"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 md:px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">No bookings found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>