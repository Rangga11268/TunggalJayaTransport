<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Custom Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Generate Custom Report</h3>
                        <a href="{{ route('admin.reports.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Back to Reports
                        </a>
                    </div>
                    
                    <!-- Report Generation Form -->
                    <form method="POST" action="{{ route('admin.reports.custom.generate') }}" class="mb-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                                <select name="report_type" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                    <option value="">Select Report Type</option>
                                    <option value="bookings" {{ (isset($reportType) && $reportType == 'bookings') ? 'selected' : '' }}>Bookings Report</option>
                                    <option value="revenue" {{ (isset($reportType) && $reportType == 'revenue') ? 'selected' : '' }}>Revenue Report</option>
                                    <option value="passengers" {{ (isset($reportType) && $reportType == 'passengers') ? 'selected' : '' }}>Passenger Report</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Route (Optional)</label>
                                <select name="route_id" class="w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">All Routes</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}" {{ (isset($routeId) && $routeId == $route->id) ? 'selected' : '' }}>
                                            {{ $route->origin }} - {{ $route->destination }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="date" name="start_date" value="{{ $startDate ?? now()->subDays(30)->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" name="end_date" value="{{ $endDate ?? now()->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bus (Optional)</label>
                                <select name="bus_id" class="w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">All Buses</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ (isset($busId) && $busId == $bus->id) ? 'selected' : '' }}>
                                            {{ $bus->name }} ({{ $bus->plate_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Generate Report
                            </button>
                        </div>
                    </form>
                    
                    <!-- Report Results -->
                    @if(isset($reportData))
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold mb-4">Report Results</h3>
                        
                        <!-- Report Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            @if($reportType == 'bookings')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-blue-800">Total Bookings</div>
                                <div class="mt-2 text-2xl font-bold text-blue-900">{{ number_format($reportData['total_bookings']) }}</div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-green-800">Total Seats Booked</div>
                                <div class="mt-2 text-2xl font-bold text-green-900">{{ number_format($reportData['total_seats']) }}</div>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-yellow-800">Avg. Seats per Booking</div>
                                <div class="mt-2 text-2xl font-bold text-yellow-900">{{ $reportData['total_bookings'] > 0 ? number_format($reportData['total_seats'] / $reportData['total_bookings'], 1) : '0' }}</div>
                            </div>
                            @elseif($reportType == 'revenue')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-blue-800">Total Revenue</div>
                                <div class="mt-2 text-2xl font-bold text-blue-900">Rp. {{ number_format($reportData['total_revenue'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-green-800">Average Booking Value</div>
                                <div class="mt-2 text-2xl font-bold text-green-900">Rp. {{ number_format($reportData['avg_booking_value'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-yellow-800">Total Bookings</div>
                                <div class="mt-2 text-2xl font-bold text-yellow-900">{{ number_format(count($reportData['daily_revenue'])) }}</div>
                            </div>
                            @elseif($reportType == 'passengers')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-blue-800">Total Passengers</div>
                                <div class="mt-2 text-2xl font-bold text-blue-900">{{ number_format($reportData['total_passengers']) }}</div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-green-800">Most Popular Route</div>
                                <div class="mt-2 text-lg font-bold text-green-900">
                                    @if(!empty($reportData['route_passengers']))
                                        @php
                                            $maxRoute = array_keys($reportData['route_passengers'], max($reportData['route_passengers']))[0];
                                        @endphp
                                        {{ $maxRoute }} ({{ number_format(max($reportData['route_passengers'])) }})
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-yellow-800">Most Popular Bus</div>
                                <div class="mt-2 text-lg font-bold text-yellow-900">
                                    @if(!empty($reportData['bus_passengers']))
                                        @php
                                            $maxBus = array_keys($reportData['bus_passengers'], max($reportData['bus_passengers']))[0];
                                        @endphp
                                        {{ $maxBus }} ({{ number_format(max($reportData['bus_passengers'])) }})
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Report Details -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium mb-4">
                                Report Details 
                                @if(isset($selectedRoute))
                                    for Route: {{ $selectedRoute->origin }} - {{ $selectedRoute->destination }}
                                @endif
                                @if(isset($selectedBus))
                                    for Bus: {{ $selectedBus->name }} ({{ $selectedBus->plate_number }})
                                @endif
                                ({{ $startDate }} to {{ $endDate }})
                            </h4>
                            
                            @if($reportType == 'bookings' && !empty($reportData['daily_bookings']))
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seats Booked</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($reportData['daily_bookings'] as $date => $data)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Carbon\Carbon::parse($date)->format('M j, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($data['count']) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($data['seats']) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @elseif($reportType == 'revenue' && !empty($reportData['daily_revenue']))
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($reportData['daily_revenue'] as $date => $revenue)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Carbon\Carbon::parse($date)->format('M j, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp. {{ number_format($revenue, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @elseif($reportType == 'passengers')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Passengers by Route</h5>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passengers</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($reportData['route_passengers'] as $route => $passengers)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $route }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($passengers) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Passengers by Bus</h5>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passengers</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($reportData['bus_passengers'] as $bus => $passengers)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bus }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($passengers) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-8 text-gray-500">
                                No data available for the selected criteria
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>