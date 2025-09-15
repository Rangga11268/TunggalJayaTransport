<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-xl transform transition duration-300 hover:scale-105">
                    <div class="p-5 md:p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <i class="fas fa-ticket-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl md:text-3xl font-bold text-gray-800">128</div>
                                <div class="text-sm md:text-base text-gray-500">Total Bookings</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg rounded-xl transform transition duration-300 hover:scale-105">
                    <div class="p-5 md:p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl md:text-3xl font-bold text-gray-800">Rp. 245M</div>
                                <div class="text-sm md:text-base text-gray-500">Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg rounded-xl transform transition duration-300 hover:scale-105">
                    <div class="p-5 md:p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                <i class="fas fa-route text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl md:text-3xl font-bold text-gray-800">42</div>
                                <div class="text-sm md:text-base text-gray-500">Active Routes</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg rounded-xl transform transition duration-300 hover:scale-105">
                    <div class="p-5 md:p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl md:text-3xl font-bold text-gray-800">1,248</div>
                                <div class="text-sm md:text-base text-gray-500">Registered Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6 md:mb-8">
                <div class="p-5 md:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800">Recent Activities</h3>
                        <a href="#" class="text-blue-500 hover:text-blue-700 text-sm">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Activity</th>
                                    <th scope="col"
                                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th scope="col"
                                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">New booking created</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">John Doe</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        2 minutes ago
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">News article published</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Admin User</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        1 hour ago
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">Bus schedule updated</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Manager User</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        3 hours ago
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">New user registered</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Customer User</div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        5 hours ago
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-5 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                        <a href="#"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-center shadow-md transform transition duration-300 hover:scale-105">
                            <i class="fas fa-plus-circle mb-1 block"></i>
                            <span>Create Booking</span>
                        </a>
                        <a href="#"
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-center shadow-md transform transition duration-300 hover:scale-105">
                            <i class="fas fa-newspaper mb-1 block"></i>
                            <span>Add News</span>
                        </a>
                        <a href="#"
                            class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-center shadow-md transform transition duration-300 hover:scale-105">
                            <i class="fas fa-route mb-1 block"></i>
                            <span>Manage Routes</span>
                        </a>
                        <a href="#"
                            class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-center shadow-md transform transition duration-300 hover:scale-105">
                            <i class="fas fa-chart-bar mb-1 block"></i>
                            <span>View Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
