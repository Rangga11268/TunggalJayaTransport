<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Schedule #{{ $schedule->id }}</h3>
                        <a href="{{ route('admin.schedule-management.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Schedules
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Schedule Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-bold mb-4 text-gray-800">Schedule Information</h4>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bus:</span>
                                    <span class="font-medium">{{ $schedule->bus->name ?? 'N/A' }} ({{ $schedule->bus->plate_number ?? 'N/A' }})</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Route:</span>
                                    <span class="font-medium">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Schedule Type:</span>
                                    <span class="font-medium">
                                        @if($schedule->is_weekly)
                                            Weekly Schedule
                                        @elseif($schedule->is_daily)
                                            Daily Recurring Schedule
                                        @else
                                            Daily Schedule
                                        @endif
                                    </span>
                                </div>
                                
                                @if($schedule->is_weekly && $schedule->day_of_week !== null)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Day of Week:</span>
                                    <span class="font-medium">{{ Carbon\Carbon::create()->dayOfWeek($schedule->day_of_week)->format('l') }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Departure Time:</span>
                                    <span class="font-medium">{{ $schedule->getDepartureTimeWIB()->format('d M Y H:i') }} (WIB)</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Arrival Time:</span>
                                    <span class="font-medium">{{ $schedule->getArrivalTimeWIB()->format('d M Y H:i') }} (WIB)</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Price:</span>
                                    <span class="font-medium">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">
                                        @if($schedule->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @elseif($schedule->status === 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Delayed
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Has Departed:</span>
                                    <span class="font-medium">
                                        @if($hasDeparted)
                                            <span class="text-red-600 font-bold">YES</span>
                                        @else
                                            <span class="text-green-600 font-bold">NO</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Available for Booking:</span>
                                    <span class="font-medium">
                                        @if($isAvailable)
                                            <span class="text-green-600 font-bold">YES</span>
                                        @else
                                            <span class="text-red-600 font-bold">NO</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Capacity Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-bold mb-4 text-gray-800">Capacity Information</h4>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bus Capacity:</span>
                                    <span class="font-medium">{{ $schedule->bus->capacity ?? 0 }} seats</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booked Seats:</span>
                                    <span class="font-medium">{{ $bookedSeats }} seats</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Available Seats:</span>
                                    <span class="font-medium">{{ $availableSeats }} seats</span>
                                </div>
                                
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-blue-600 h-4 rounded-full" 
                                         style="width: {{ ($availableSeats / max(1, $schedule->bus->capacity ?? 1)) * 100 }}%">
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-500 mt-2">
                                    {{ round(($availableSeats / max(1, $schedule->bus->capacity ?? 1)) * 100, 1) }}% available
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bookings Section -->
                    <div class="mt-8">
                        <h4 class="text-md font-bold mb-4 text-gray-800">Bookings ({{ $schedule->bookings->count() }})</h4>
                        
                        @if($schedule->bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passenger</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seats</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
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
                                            {{ $booking->number_of_seats }} seats
                                            @if($booking->seat_numbers)
                                            <div class="text-xs">({{ $booking->seat_numbers }})</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($booking->booking_status === 'confirmed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Confirmed
                                                </span>
                                            @elseif($booking->booking_status === 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            @endif
                                            
                                            @if($booking->payment_status === 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ml-1">
                                                    Paid
                                                </span>
                                            @elseif($booking->payment_status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-1">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 ml-1">
                                                    {{ ucfirst($booking->payment_status) }}
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
                            No bookings found for this schedule.
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Schedule
                        </a>
                        
                        @if($schedule->hasDeparted() && $schedule->is_daily && !$schedule->is_weekly)
                            <form action="{{ route('admin.schedules.create-next-day', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Create Next Day Schedule
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                    onclick="return confirm('Are you sure you want to delete this schedule?')">
                                Delete Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>