<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bus Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="busForm" action="{{ route('admin.buses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Bus</label>
                                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="plate_number" class="block text-sm font-medium text-gray-700">Nomor Plat</label>
                                    <input type="text" name="plate_number" id="plate_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('plate_number') }}" required>
                                    @error('plate_number')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="bus_type" class="block text-sm font-medium text-gray-700">Tipe Bus</label>
                                    <input type="text" name="bus_type" id="bus_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('bus_type') }}" required>
                                    @error('bus_type')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                                    <input type="number" name="capacity" id="capacity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('capacity') }}" required>
                                    @error('capacity')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                                    <input type="number" name="year" id="year" min="1900" max="{{ date('Y') + 2 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" value="{{ old('year') }}">
                                    @error('year')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar Bus</label>
                                    <input type="file" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly">
                                    @error('image')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Seleksi Driver -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Driver</label>
                            <p class="text-sm text-gray-500 mb-2">Catatan: Setiap driver hanya dapat ditugaskan ke satu bus pada satu waktu.</p>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($drivers as $driver)
                                    @php
                                        $isAssignedToOtherBus = in_array($driver->id, $assignedDrivers);
                                    @endphp
                                    <div class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50 {{ $isAssignedToOtherBus ? 'bg-red-50 opacity-75' : '' }}">
                                        <input 
                                            type="checkbox" 
                                            name="drivers[]" 
                                            value="{{ $driver->id }}" 
                                            id="driver_{{ $driver->id }}" 
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly"
                                            {{ $isAssignedToOtherBus ? 'disabled' : '' }}
                                        >
                                        <label for="driver_{{ $driver->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $driver->name }} ({{ $driver->license_number }})
                                            @if($isAssignedToOtherBus)
                                                <span class="text-red-500 text-xs">(Sudah ditugaskan)</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('drivers')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Seleksi Kondektur -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kondektur</label>
                            <p class="text-sm text-gray-500 mb-2">Catatan: Setiap kondektur hanya dapat ditugaskan ke satu bus pada satu waktu.</p>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($conductors as $conductor)
                                    @php
                                        $isAssignedToOtherBus = in_array($conductor->id, $assignedConductors);
                                    @endphp
                                    <div class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50 {{ $isAssignedToOtherBus ? 'bg-red-50 opacity-75' : '' }}">
                                        <input 
                                            type="checkbox" 
                                            name="conductors[]" 
                                            value="{{ $conductor->id }}" 
                                            id="conductor_{{ $conductor->id }}" 
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 touch-friendly"
                                            {{ $isAssignedToOtherBus ? 'disabled' : '' }}
                                        >
                                        <label for="conductor_{{ $conductor->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $conductor->name }} ({{ $conductor->employee_id }})
                                            @if($isAssignedToOtherBus)
                                                <span class="text-red-500 text-xs">(Sudah ditugaskan)</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('conductors')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
                            <a href="{{ route('admin.buses.index') }}" class="text-gray-600 hover:text-gray-800 w-full sm:w-auto text-center touch-friendly">
                                ← Kembali ke Bus
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto touch-friendly">
                                Tambah Bus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const busForm = document.getElementById('busForm');
        const plateNumberInput = document.getElementById('plate_number');
        
        // Add form submission validation
        busForm.addEventListener('submit', function(e) {
            const plateNumber = plateNumberInput.value.trim();
            
            if (!plateNumber) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Nomor Plat Diperlukan',
                    text: 'Silakan masukkan nomor plat.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            // Check if plate number already exists in the database
            // This is a client-side check to provide immediate feedback
            // The server-side validation will still be the authoritative check
        });
    });
    </script>
    @endsection
</x-app-layout>