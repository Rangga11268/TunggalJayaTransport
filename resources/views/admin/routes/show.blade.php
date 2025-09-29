<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Route') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">{{ $route->name }}</h1>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Route Details</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Origin:</dt>
                                    <dd class="text-gray-900">{{ $route->origin }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Destination:</dt>
                                    <dd class="text-gray-900">{{ $route->destination }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Distance:</dt>
                                    <dd class="text-gray-900">
                                        @if($route->distance)
                                            {{ $route->distance }} km
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Duration:</dt>
                                    <dd class="text-gray-900">
                                        @if($route->duration)
                                            {{ $route->duration }} minutes
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium mb-2">Description</h3>
                            @if($route->description)
                                <p class="text-gray-600">{{ $route->description }}</p>
                            @else
                                <p class="text-gray-500 italic">No description provided.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('admin.routes.index') }}" class="text-gray-600 hover:text-gray-800">
                            ← Back to Routes
                        </a>
                        <div>
                            <a href="{{ route('admin.routes.edit', $route) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form id="delete-form" action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="event.preventDefault(); handleDelete('delete-form', 'Hapus Rute?', 'Apakah Anda yakin ingin menghapus rute ini? Tindakan ini tidak dapat dibatalkan.')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Related Schedules -->
                    @if($route->schedules->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium mb-4">Related Schedules</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($route->schedules as $schedule)
                                    <tr class="{{ $schedule->hasDeparted() && !$schedule->is_daily ? 'bg-red-50' : '' }}">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $schedule->bus->plate_number }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($schedule->is_daily)
                                                @php
                                                    // For daily recurring schedules, show today or tomorrow based on time
                                                    $today = \Carbon\Carbon::today('Asia/Jakarta');
                                                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                                                    $todayDeparture = $today->copy()->setTimeFromTimeString($schedule->departure_time->format('H:i:s'));
                                                    
                                                    if ($todayDeparture->isFuture()) {
                                                        echo $todayDeparture->format('d M Y H:i');
                                                    } else {
                                                        $tomorrowDeparture = $today->copy()->addDay()->setTimeFromTimeString($schedule->departure_time->format('H:i:s'));
                                                        echo $tomorrowDeparture->format('d M Y H:i');
                                                    }
                                                    echo ' <span class="text-xs text-gray-500 ml-1">(WIB)</span>';
                                                @endphp
                                            @else
                                                {{ $schedule->getDepartureTimeWIB()->format('d M Y H:i') }}
                                                <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                            @endif
                                            @if($schedule->hasDeparted() && !$schedule->is_daily)
                                                <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                    DEPARTED
                                                </span>
                                            @endif
                                            @if($schedule->is_daily)
                                                <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                    DAILY
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($schedule->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($schedule->status === 'active')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @elseif($schedule->status === 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Delayed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.schedules.show', $schedule) }}" class="text-blue-600 hover:text-blue-900 mr-2" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-yellow-600 hover:text-yellow-900 mr-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
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