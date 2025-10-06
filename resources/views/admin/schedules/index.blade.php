<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Jadwal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold">Jadwal</h3>
                        <a href="{{ route('admin.schedules.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center touch-friendly">
                            Tambah Jadwal Baru
                        </a>
                    </div>
                    
                    <!-- Filter Form -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                                <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Bus</option>
                                    @foreach(App\Models\Bus::all() as $bus)
                                        <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="route_id" class="block text-sm font-medium text-gray-700">Rute</label>
                                <select name="route_id" id="route_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Rute</option>
                                    @foreach(App\Models\Route::all() as $route)
                                        <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>{{ $route->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Tertunda</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Filter
                                </button>
                                <a href="{{ route('admin.schedules.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Atur Ulang
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Schedules Table -->
                    <div class="overflow-x-auto">
                        @if(session('warning'))
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Peringatan!</strong>
                                <span class="block sm:inline">{{ session('warning') }}</span>
                            </div>
                        @endif
                        
                        @if(session('create_success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Sukses!</strong>
                                <span class="block sm:inline">{{ session('create_success') }}</span>
                            </div>
                        @endif
                        
                        @if(session('delete_success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Sukses!</strong>
                                <span class="block sm:inline">{{ session('delete_success') }}</span>
                            </div>
                        @endif
                        
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keberangkatan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kedatangan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesanan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($schedules as $schedule)
                                    <tr class="{{ $schedule->hasDeparted() ? 'bg-red-50' : '' }}">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $schedule->bus->plate_number }}</div>
                                            @if($schedule->hasDeparted() && !$schedule->is_daily)
                                                <div class="text-xs text-red-600 font-semibold">SUDAH BERANGKAT</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->route->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</div>
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
                                                <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                    HARIAN
                                                </span>
                                            @else
                                                {{ $schedule->getDepartureTimeWIB()->format('d M Y H:i') }}
                                                <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                            @endif
                                            @if($schedule->hasDeparted() && !$schedule->is_daily)
                                                <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                    BERANGKAT
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->getArrivalTimeWIB()->format('H:i') }} (WIB)
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($schedule->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="text-sm text-gray-900">{{ $schedule->bookings->count() }} pemesanan</div>
                                            <div class="text-xs text-gray-500">{{ $schedule->getAvailableSeatsCount() }} kursi tersedia</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($schedule->status === 'active')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @elseif($schedule->status === 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Tertunda
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-row gap-2 action-buttons">
                                                <a href="{{ route('admin.schedules.show', $schedule) }}" class="view-icon" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="edit-icon" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($schedule->hasDeparted() && $schedule->is_daily)
                                                    <form id="create-next-day-form-{{ $schedule->id }}" action="{{ route('admin.schedules.create-next-day', $schedule) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="button" class="text-green-600 hover:text-green-900" onclick="handleDelete('create-next-day-form-{{ $schedule->id }}', 'Buat Jadwal Hari Berikutnya?', 'Apakah Anda yakin ingin membuat jadwal untuk hari berikutnya berdasarkan jadwal ini?')" title="Buat Jadwal Hari Berikutnya">
                                                            <i class="fas fa-calendar-plus"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <form id="delete-form-{{ $schedule->id }}" action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-icon" onclick="handleDelete('delete-form-{{ $schedule->id }}', 'Hapus Jadwal?', 'Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Tidak ada jadwal ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>