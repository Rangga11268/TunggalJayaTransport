<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Sales Overview</h3>
                        <a href="{{ route('admin.reports.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Back to Reports
                        </a>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-blue-800">Total Revenue</div>
                            <div class="mt-2 text-2xl font-bold text-blue-900">Rp. {{ number_format($salesData->sum('total'), 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-800">Total Bookings</div>
                            <div class="mt-2 text-2xl font-bold text-green-900">{{ $salesData->count() }}</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-yellow-800">Avg. Booking Value</div>
                            <div class="mt-2 text-2xl font-bold text-yellow-900">Rp. {{ $salesData->count() > 0 ? number_format($salesData->sum('total') / $salesData->count(), 0, ',', '.') : 0 }}</div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-purple-800">This Month</div>
                            <div class="mt-2 text-2xl font-bold text-purple-900">Rp. {{ number_format($salesData->filter(function($item) { return \Carbon\Carbon::parse($item->date)->isSameMonth(now()); })->sum('total'), 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-4">Revenue Trend (Last 30 Days)</h4>
                        <div class="bg-white border border-gray-300 rounded-lg p-4 h-80">
                            <canvas id="salesChart" data-sales="{{ json_encode($chartData) }}"></canvas>
                        </div>
                    </div>
                    
                    <!-- Recent Sales Table -->
                    <div>
                        <h4 class="text-md font-medium mb-4">Recent Sales</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->user->name ?? $booking->passenger_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->schedule->route->origin ?? '' }} - {{ $booking->schedule->route->destination ?? '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->created_at->format('M j, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No recent sales data available
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