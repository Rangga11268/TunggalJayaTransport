<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Driver Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold">Driver Information</h3>
                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <a href="{{ route('admin.drivers.edit', $driver) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center">
                                Edit
                            </a>
                            <a href="{{ route('admin.drivers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col items-center">
                            <div class="mb-4 w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                                <div class="flex justify-center">
                                    @if($driver->getFirstMediaUrl('drivers'))
                                        <img src="{{ $driver->getFirstMediaUrl('drivers') }}" alt="Driver Photo" class="w-48 h-48 object-cover rounded">
                                    @else
                                        <div class="w-48 h-48 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->name }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Employee ID</label>
                                <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->employee_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">License Number</label>
                                <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->license_number }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->phone }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->email ?? '-' }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <div class="mt-1 text-lg bg-gray-50 p-2 rounded min-h-[50px]">{{ $driver->address ?? '-' }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    @if($driver->status === 'active')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Created At</label>
                                    <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->created_at->format('d M Y H:i') }}</div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                    <div class="mt-1 text-lg bg-gray-50 p-2 rounded">{{ $driver->updated_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Buses -->
                    @if($driver->buses->count() > 0)
                    <div class="mt-8">
                        <h4 class="text-md font-bold mb-4">Assigned Buses</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus Name</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($driver->buses as $bus)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $bus->name }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $bus->plate_number }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $bus->bus_type }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $bus->capacity }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>