@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Complete Schedule</h1>
        <div class="flex items-center space-x-2">
            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                <i class="fas fa-sync-alt mr-1"></i>Daily Reset
            </span>
            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                <i class="fas fa-calendar-week mr-1"></i>Weekly Schedules
            </span>
        </div>
    </div>
    
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
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>
    
    <!-- Schedule Legend -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center mb-2 md:mb-0">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm">Daily Schedule</span>
            </div>
            <div class="flex items-center mb-2 md:mb-0">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm">Weekly Schedule</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                <span class="text-sm">Departed</span>
            </div>
        </div>
    </div>
    
    <!-- Schedule Results -->
    @if($schedules->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Schedule</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Route</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Bus</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Availability</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($schedules as $schedule)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $schedule->is_weekly ? 'bg-green-100' : 'bg-blue-100' }}">
                                    <i class="fas fa-{{ $schedule->is_weekly ? 'calendar-week' : 'clock' }} text-{{ $schedule->is_weekly ? 'green' : 'blue' }}-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->departure_time->format('H:i') }}
                                        <span class="text-gray-500 mx-1">→</span>
                                        {{ $schedule->arrival_time->format('H:i') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->departure_time->format('l, F j') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->route->origin }}</div>
                            <div class="text-sm text-gray-500">{{ $schedule->route->destination }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $schedule->bus->plate_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($schedule->hasDeparted())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Departed
                                </span>
                            @else
                                <div class="text-sm text-gray-900">{{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }} seats</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($schedule->getAvailableSeatsCount() / max(1, $schedule->bus->capacity)) * 100 }}%"></div>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp. {{ number_format($schedule->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($schedule->hasDeparted())
                                <span class="text-gray-400">Unavailable</span>
                            @elseif($schedule->getAvailableSeatsCount() > 0)
                                <a href="{{ route('frontend.booking.show', $schedule->id) }}" class="text-blue-600 hover:text-blue-900">
                                    Book Now
                                </a>
                            @else
                                <span class="text-gray-400">Full</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $schedules->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-1">No schedules found</h3>
        <p class="text-gray-500">There are no available schedules matching your search criteria.</p>
    </div>
    @endif
</div>
@endsection