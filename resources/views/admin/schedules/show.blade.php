<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">Schedule Details</h1>
                        @if($schedule->hasDeparted())
                            <div class="mt-2 bg-red-100 text-red-800 text-sm font-semibold px-3 py-2 rounded inline-flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                THIS SCHEDULE HAS ALREADY DEPARTED
                            </div>
                        @endif
                        @if($schedule->is_weekly)
                            <div class="mt-2 bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-2 rounded inline-flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                WEEKLY SCHEDULE
                            </div>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Bus Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Bus Name:</dt>
                                    <dd class="text-gray-900">{{ $schedule->bus->name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Plate Number:</dt>
                                    <dd class="text-gray-900">{{ $schedule->bus->plate_number }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Bus Type:</dt>
                                    <dd class="text-gray-900">{{ $schedule->bus->bus_type }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Capacity:</dt>
                                    <dd class="text-gray-900">{{ $schedule->bus->capacity }} seats</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium mb-2">Route Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Route Name:</dt>
                                    <dd class="text-gray-900">{{ $schedule->route->name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Origin:</dt>
                                    <dd class="text-gray-900">{{ $schedule->route->origin }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Destination:</dt>
                                    <dd class="text-gray-900">{{ $schedule->route->destination }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Distance:</dt>
                                    <dd class="text-gray-900">{{ $schedule->route->distance }} km</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-2">Schedule Details</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Departure Time:</dt>
                                <dd class="text-gray-900">
                                    @if($schedule->is_weekly && $schedule->day_of_week !== null)
                                        @php
                                            $nextDate = $schedule->getNextAvailableDate();
                                            if ($nextDate) {
                                                $displayDateTime = $nextDate->copy()->setTimeFromTimeString($schedule->departure_time->format('H:i:s'));
                                                echo $displayDateTime->format('d M Y H:i');
                                            } else {
                                                echo $schedule->departure_time->format('d M Y H:i');
                                            }
                                        @endphp
                                    @else
                                        {{ $schedule->departure_time->format('d M Y H:i') }}
                                    @endif
                                </dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Arrival Time:</dt>
                                <dd class="text-gray-900">
                                    @if($schedule->is_weekly && $schedule->day_of_week !== null)
                                        @php
                                            $nextDate = $schedule->getNextAvailableDate();
                                            if ($nextDate) {
                                                $displayDateTime = $nextDate->copy()->setTimeFromTimeString($schedule->arrival_time->format('H:i:s'));
                                                echo $displayDateTime->format('d M Y H:i');
                                            } else {
                                                echo $schedule->arrival_time->format('d M Y H:i');
                                            }
                                        @endphp
                                    @else
                                        {{ $schedule->arrival_time->format('d M Y H:i') }}
                                    @endif
                                </dd>
                            </div>
                            @if($schedule->is_weekly)
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Day of Week:</dt>
                                <dd class="text-gray-900">
                                    @php
                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    @endphp
                                    {{ $days[$schedule->day_of_week] ?? 'Not set' }}
                                </dd>
                            </div>
                            @endif
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Price:</dt>
                                <dd class="text-gray-900">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Status:</dt>
                                <dd class="text-gray-900">
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
                                </dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-2">Booking Statistics</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-500">Total Bookings</dt>
                                <dd class="text-2xl font-bold text-blue-700">{{ $schedule->bookings->count() }}</dd>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-500">Confirmed Bookings</dt>
                                <dd class="text-2xl font-bold text-green-700">{{ $schedule->bookings->where('booking_status', 'confirmed')->count() }}</dd>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-500">Available Seats</dt>
                                <dd class="text-2xl font-bold text-yellow-700">{{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($schedule->bookings->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-2">Recent Bookings</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passenger</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seats</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedule->bookings->take(5) as $booking)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $booking->passenger_name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $booking->number_of_seats }} seats</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($booking->booking_status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->booking_status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($schedule->bookings->count() > 5)
                        <div class="mt-2 text-sm text-gray-500">
                            Showing 5 of {{ $schedule->bookings->count() }} bookings. <a href="{{ route('admin.bookings.index', ['schedule_id' => $schedule->id]) }}" class="text-blue-600 hover:underline">View all</a>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('admin.schedules.index') }}" class="text-gray-600 hover:text-gray-800">
                            ← Back to Schedules
                        </a>
                        <div>
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this schedule?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>