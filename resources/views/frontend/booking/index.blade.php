@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Book Your Ticket</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Find and book your next journey with Tunggal Jaya Transport. Comfortable, reliable, and affordable travel options.</p>
    </div>
    
    <!-- Link to Complete Schedule -->
    <div class="mb-8 text-center">
        <a href="{{ route('frontend.booking.schedules') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 border border-transparent rounded-lg font-semibold text-white hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:scale-105 transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            View Complete Schedule
        </a>
    </div>
    
    <!-- Info about schedule reset -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-4 mb-8">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Schedule Information:</strong> Daily schedules reset automatically each day. Weekly schedules repeat on their designated days. 
                    Once a bus has departed, tickets can no longer be purchased for that schedule.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-10">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Search for Schedules</h2>
        <form method="GET" action="{{ route('frontend.booking.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700 mb-2">Origin</label>
                <select id="origin" name="origin" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                    <option value="">Select Origin</option>
                    @foreach($origins as $originOption)
                        <option value="{{ $originOption }}" {{ request()->get('origin') == $originOption ? 'selected' : '' }}>{{ $originOption }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                <select id="destination" name="destination" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                    <option value="">Select Destination</option>
                    @foreach($destinations as $destinationOption)
                        <option value="{{ $destinationOption }}" {{ request()->get('destination') == $destinationOption ? 'selected' : '' }}>{{ $destinationOption }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="date" name="date" value="{{ request()->get('date') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    @if(request()->has('origin') && request()->has('destination'))
    <div class="bg-white rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Available Schedules</h2>
            <div class="text-sm text-gray-500">
                @if(request()->get('date'))
                    {{ \Carbon\Carbon::parse(request()->get('date'))->format('l, F j, Y') }}
                @endif
            </div>
        </div>
        
        @if(isset($validPair) && !$validPair)
        <div class="text-center py-10 bg-red-50 rounded-lg">
            <div class="text-red-600 text-5xl mb-4">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <p class="text-red-600 font-bold text-xl">No routes available</p>
            <p class="text-gray-600 mt-2">Between {{ request()->get('origin') }} and {{ request()->get('destination') }}</p>
            <p class="text-gray-600 mt-4">Please select a different origin/destination combination.</p>
        </div>
        @elseif(isset($schedules) && $schedules->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Route</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Departure</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Arrival</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Bus Type</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Available Seats</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($schedules as $schedule)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-route text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->route->origin }}</div>
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-arrow-down mr-1 text-xs"></i> {{ $schedule->route->destination }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->departure_time->format('H:i') }}</div>
                            <div class="text-sm text-gray-500">Terminal {{ $schedule->departure_terminal ?? '1' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->arrival_time->format('H:i') }}</div>
                            <div class="text-sm text-gray-500">Terminal {{ $schedule->arrival_terminal ?? '1' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->bus_type ?? 'Standard' }}</div>
                            <div class="text-sm text-gray-500">{{ $schedule->bus->plate_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($schedule->getAvailableSeatsCount() / $schedule->bus->capacity) * 100 }}%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-gray-900">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($schedule->hasDeparted())
                                <span class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 cursor-not-allowed">
                                    Departed
                                </span>
                            @elseif($schedule->getAvailableSeatsCount() > 0)
                            <a href="{{ route('frontend.booking.show', $schedule->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 border border-transparent rounded-md font-semibold text-white hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                                Select
                            </a>
                            @else
                            <span class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 cursor-not-allowed">
                                Full
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-10 bg-gray-50 rounded-lg">
            <div class="text-gray-400 text-5xl mb-4">
                <i class="fas fa-search"></i>
            </div>
            <p class="text-gray-600 text-lg">No schedules found for the selected route.</p>
            <p class="text-gray-500 mt-2">Please try different origin/destination or date.</p>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection