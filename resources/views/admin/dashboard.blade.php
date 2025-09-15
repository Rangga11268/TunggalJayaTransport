<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-blue-500">{{ $totalBookings }}</div>
                            <div class="ml-4 text-gray-500">Total Bookings</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-green-500">{{ $formattedRevenue }}</div>
                            <div class="ml-4 text-gray-500">Revenue</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-yellow-500">{{ $activeRoutes }}</div>
                            <div class="ml-4 text-gray-500">Active Routes</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-purple-500">{{ $registeredUsers }}</div>
                            <div class="ml-4 text-gray-500">Registered Users</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Recent Activities</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentActivities as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $activity['description'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $activity['user'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity['time'] }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No recent activities found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.bookings.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded text-center">
                            Create Booking
                        </a>
                        <a href="{{ route('admin.news.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded text-center">
                            Add News
                        </a>
                        <a href="{{ route('admin.buses.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Buses
                        </a>
                        <a href="{{ route('admin.routes.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Routes
                        </a>
                        <a href="{{ route('admin.schedules.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Schedules
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Bookings
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Users
                        </a>
                        <a href="{{ route('admin.facilities.index') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Facilities
                        </a>
                        <a href="{{ route('admin.drivers.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded text-center">
                            Manage Drivers
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-6 rounded text-center">
                            View Reports
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-4 px-6 rounded text-center">
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>