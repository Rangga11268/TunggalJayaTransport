<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Occupancy Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Occupancy Overview</h3>
                        <a href="{{ route('admin.reports.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Back to Reports
                        </a>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-blue-800">Average Occupancy</div>
                            <div class="mt-2 text-2xl font-bold text-blue-900">
                                @php
                                    $averageOccupancy = count($occupancyData) > 0 ? array_sum(array_column($occupancyData, 'occupancy_rate')) / count($occupancyData) : 0;
                                @endphp
                                {{ number_format($averageOccupancy, 2) }}%
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-800">Highest Route</div>
                            <div class="mt-2 text-2xl font-bold text-green-900">
                                @if(count($occupancyData) > 0)
                                    {{ $occupancyData[0]['route'] }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-yellow-800">Lowest Route</div>
                            <div class="mt-2 text-2xl font-bold text-yellow-900">
                                @if(count($occupancyData) > 0)
                                    {{ end($occupancyData)['route'] }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-4">Top 10 Occupancy Rates by Bus</h4>
                        <div class="bg-white border border-gray-300 rounded-lg p-4 h-80">
                            <canvas id="occupancyChart" data-occupancy="{{ json_encode($occupancyData) }}"></canvas>
                        </div>
                    </div>
                    
                    <!-- Occupancy by Bus Table -->
                    <div>
                        <h4 class="text-md font-medium mb-4">Occupancy by Bus</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booked Seats</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupancy Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($occupancyData as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['bus_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['plate_number'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['route'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['capacity'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['booked_seats'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                    @php
                                                        $rate = $data['occupancy_rate'];
                                                        $color = $rate >= 80 ? 'bg-green-600' : ($rate >= 60 ? 'bg-yellow-600' : 'bg-red-600');
                                                    @endphp
                                                    <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ min($rate, 100) }}%"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">{{ number_format($rate, 2) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No occupancy data available
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>