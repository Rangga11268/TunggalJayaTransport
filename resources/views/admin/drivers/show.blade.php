<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Driver') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">{{ $driver->name }}</h1>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Personal Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Name:</dt>
                                    <dd class="text-gray-900">{{ $driver->name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">License Number:</dt>
                                    <dd class="text-gray-900">{{ $driver->license_number }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Phone:</dt>
                                    <dd class="text-gray-900">{{ $driver->phone }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Email:</dt>
                                    <dd class="text-gray-900">{{ $driver->email ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Status:</dt>
                                    <dd class="text-gray-900">
                                        @if($driver->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium mb-2">Address</h3>
                            @if($driver->address)
                                <p class="text-gray-600">{{ $driver->address }}</p>
                            @else
                                <p class="text-gray-500 italic">No address provided.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('admin.drivers.index') }}" class="text-gray-600 hover:text-gray-800">
                            ← Back to Drivers
                        </a>
                        <div>
                            <a href="{{ route('admin.drivers.edit', $driver) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this driver?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>