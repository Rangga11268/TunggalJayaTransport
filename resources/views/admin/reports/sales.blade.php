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
                            <div class="mt-2 text-2xl font-bold text-blue-900">Rp. 245,000,000</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-800">Total Bookings</div>
                            <div class="mt-2 text-2xl font-bold text-green-900">1,248</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-yellow-800">Avg. Booking Value</div>
                            <div class="mt-2 text-2xl font-bold text-yellow-900">Rp. 196,314</div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-purple-800">This Month</div>
                            <div class="mt-2 text-2xl font-bold text-purple-900">Rp. 24,500,000</div>
                        </div>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-4">Revenue Trend (Last 30 Days)</h4>
                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-8 h-64 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="mt-2 text-gray-500">Sales chart visualization would appear here</p>
                                <p class="text-sm text-gray-400">Using Chart.js library</p>
                            </div>
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
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BK123456</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">John Doe</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jakarta - Bandung</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sep 10, 2025</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp. 150,000</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BK123457</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jane Smith</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Surabaya - Malang</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sep 9, 2025</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp. 100,000</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BK123458</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Robert Johnson</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yogyakarta - Solo</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sep 8, 2025</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp. 80,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>