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
                            <div class="mt-2 text-2xl font-bold text-blue-900">85%</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-800">Highest Route</div>
                            <div class="mt-2 text-2xl font-bold text-green-900">Jakarta - Bandung</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-yellow-800">Lowest Route</div>
                            <div class="mt-2 text-2xl font-bold text-yellow-900">Yogyakarta - Solo</div>
                        </div>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-4">Occupancy by Route</h4>
                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-8 h-64 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-gray-500">Occupancy chart visualization would appear here</p>
                                <p class="text-sm text-gray-400">Using Chart.js library</p>
                            </div>
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
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Executive Bus 1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">B 1234 XYZ</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jakarta - Bandung</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">40</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">34</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: 85%"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">85%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Business Bus 1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">B 5678 ABC</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Surabaya - Malang</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">35</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">28</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-yellow-600 h-2.5 rounded-full" style="width: 80%"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">80%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Economy Bus 1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">B 9012 DEF</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yogyakarta - Solo</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">30</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-red-600 h-2.5 rounded-full" style="width: 60%"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">60%</span>
                                            </div>
                                        </td>
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