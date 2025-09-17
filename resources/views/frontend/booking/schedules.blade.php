@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Complete Schedule</h1>
    
    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Search for Schedules</h2>
        <form method="GET" action="{{ route('frontend.booking.schedules') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700">Origin</label>
                <select id="origin" name="origin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Origins</option>
                    @foreach($origins as $originOption)
                        <option value="{{ $originOption }}" {{ request()->get('origin') == $originOption ? 'selected' : '' }}>{{ $originOption }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                <select id="destination" name="destination" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Destinations</option>
                    @foreach($destinations as $destinationOption)
                        <option value="{{ $destinationOption }}" {{ request()->get('destination') == $destinationOption ? 'selected' : '' }}>{{ $destinationOption }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" value="{{ request()->get('date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
        </form>
        
        <!-- Info -->
        <div class="mt-4 text-sm text-gray-600">
            <p>Showing all available schedules. Use the filters above to narrow down your search.</p>
        </div>
    </div>

    <!-- All Schedules -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">All Available Schedules</h2>
            <span class="text-sm text-gray-500">{{ $schedules->total() }} schedules found</span>
        </div>
        
        @if(isset($schedules) && $schedules->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrival</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Seats</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($schedules as $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->route->origin }} - {{ $schedule->route->destination }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $schedule->departure_time->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $schedule->arrival_time->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $schedule->bus->bus_type ?? 'Standard' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $availableSeats = $schedule->getAvailableSeatsCount();
                                $capacity = $schedule->bus->capacity;
                                $percentage = $capacity > 0 ? ($availableSeats / $capacity) * 100 : 0;
                            @endphp
                            <div class="text-sm text-gray-900">{{ $availableSeats }} / {{ $capacity }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('frontend.booking.show', $schedule->id) }}" class="text-indigo-600 hover:text-indigo-900">Select</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $schedules->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-4">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No schedules found</h3>
            <p class="mt-1 text-gray-500">No schedules match your search criteria. Please try different search parameters.</p>
            <div class="mt-6">
                <a href="{{ route('frontend.booking.schedules') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View All Schedules
                </a>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Back to Booking -->
    <div class="mb-8">
        <a href="{{ route('frontend.booking.index') }}" class="inline-flex items-center text-blue-500 hover:underline">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Booking
        </a>
    </div>
</div>
@endsection