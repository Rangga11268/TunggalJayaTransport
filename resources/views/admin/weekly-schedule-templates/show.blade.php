<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Weekly Schedule Template Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Template Details: {{ $template->name }}</h3>
                        <div>
                            <a href="{{ route('admin.weekly-schedule-templates.edit', $template) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.weekly-schedule-templates.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← Back to Templates
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Template Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium mb-4">Template Information</h4>
                            
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Name</div>
                                    <div class="w-2/3 text-sm text-gray-900">{{ $template->name }}</div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Status</div>
                                    <div class="w-2/3 text-sm">
                                        @if($template->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Created At</div>
                                    <div class="w-2/3 text-sm text-gray-900">{{ $template->created_at->format('d M Y H:i') }}</div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Updated At</div>
                                    <div class="w-2/3 text-sm text-gray-900">{{ $template->updated_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Schedule Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium mb-4">Schedule Information</h4>
                            
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Bus</div>
                                    <div class="w-2/3 text-sm text-gray-900">
                                        {{ $template->bus->name }}<br>
                                        <span class="text-gray-500">{{ $template->bus->plate_number }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Route</div>
                                    <div class="w-2/3 text-sm text-gray-900">
                                        {{ $template->route->name }}<br>
                                        <span class="text-gray-500">{{ $template->route->origin }} → {{ $template->route->destination }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Day of Week</div>
                                    <div class="w-2/3 text-sm text-gray-900">{{ $template->getDayName() }}</div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Departure Time</div>
                                    <div class="w-2/3 text-sm text-gray-900">{{ $template->departure_time->format('H:i') }}</div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Arrival Time</div>
                                    <div class="w-2/3 text-sm text-gray-900">{{ $template->arrival_time->format('H:i') }}</div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-1/3 text-sm font-medium text-gray-700">Price</div>
                                    <div class="w-2/3 text-sm text-gray-900">Rp. {{ number_format($template->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Generate Schedules Button -->
                    <div class="mt-8">
                        <a href="{{ route('admin.weekly-schedule-templates.generate-form', $template) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Generate Schedules from this Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>