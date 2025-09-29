<?php
use Carbon\Carbon;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dasbor Manajemen Jadwal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold">Ringkasan Jadwal</h3>
                        <a href="{{ route('admin.schedules.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center touch-friendly">
                            Tambah Jadwal Baru
                        </a>
                    </div>

                    <!-- Form Filter -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.schedule-management.index') }}"
                            class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                                <select name="bus_id" id="bus_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Bus</option>
                                    @foreach ($buses as $bus)
                                        <option value="{{ $bus->id }}"
                                            {{ request('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="route_id" class="block text-sm font-medium text-gray-700">Rute</label>
                                <select name="route_id" id="route_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Rute</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}"
                                            {{ request('route_id') == $route->id ? 'selected' : '' }}>
                                            {{ $route->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Dibatalkan</option>
                                    <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>
                                        Tertunda</option>
                                </select>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                                <select name="type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Tipe</option>
                                    <option value="daily" {{ request('type') == 'daily' ? 'selected' : '' }}>Harian
                                    </option>
                                    <option value="daily_recurring"
                                        {{ request('type') == 'daily_recurring' ? 'selected' : '' }}>Harian Berulang
                                    </option>
                                    
                                </select>
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="md:col-span-5 flex items-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Filter
                                </button>
                                <a href="{{ route('admin.schedule-management.index') }}"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Atur Ulang
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Kartu Ringkasan -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="text-sm text-blue-700 font-medium">Total Jadwal</div>
                            <div class="text-2xl font-bold">{{ $schedules->total() }}</div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="text-sm text-green-700 font-medium">Jadwal Aktif</div>
                            <div class="text-2xl font-bold">{{ $schedules->where('status', 'active')->count() }}</div>
                        </div>

                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="text-sm text-yellow-700 font-medium">Berangkat Hari Ini</div>
                            <div class="text-2xl font-bold">
                                @php
                                    $departedCount = 0;
                                    foreach ($schedules as $schedule) {
                                        if ($schedule->hasDeparted()) {
                                            $departedCount++;
                                        }
                                    }
                                @endphp
                                {{ $departedCount }}
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="text-sm text-red-700 font-medium">Dibatalkan</div>
                            <div class="text-2xl font-bold">{{ $schedules->where('status', 'cancelled')->count() }}
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Jadwal -->
                    <div class="overflow-x-auto">
                        @if (session('warning'))
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4"
                                role="alert">
                                <strong class="font-bold">Peringatan!</strong>
                                <span class="block sm:inline">{{ session('warning') }}</span>
                            </div>
                        @endif

                        @if (session('create_success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                                role="alert">
                                <strong class="font-bold">Sukses!</strong>
                                <span class="block sm:inline">{{ session('create_success') }}</span>
                            </div>
                        @endif

                        @if (session('delete_success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                                role="alert">
                                <strong class="font-bold">Sukses!</strong>
                                <span class="block sm:inline">{{ session('delete_success') }}</span>
                            </div>
                        @endif

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bus & Rute</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe Jadwal</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keberangkatan</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kedatangan</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kapasitas</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($schedules as $schedule)
                                    <tr class="{{ $schedule->hasDeparted() ? 'bg-red-50' : '' }}">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $schedule->bus->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $schedule->bus->plate_number ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $schedule->route->origin }} →
                                                {{ $schedule->route->destination }}</div>
                                            @if ($schedule->hasDeparted())
                                                <div class="text-xs text-red-600 font-semibold">SUDAH BERANGKAT</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($schedule->is_daily)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    HARIAN BERULANG
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    HARIAN
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($schedule->is_daily)
                                                @php
                                                    // For daily recurring schedules, show today or tomorrow based on time
                                                    $today = \Carbon\Carbon::today('Asia/Jakarta');
                                                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                                                    $todayDeparture = $today
                                                        ->copy()
                                                        ->setTimeFromTimeString(
                                                            $schedule->departure_time->format('H:i:s'),
                                                        );

                                                    if ($todayDeparture->isFuture()) {
                                                        echo $todayDeparture->format('d M Y H:i') . ' (WIB)';
                                                    } else {
                                                        $tomorrowDeparture = $today
                                                            ->copy()
                                                            ->addDay()
                                                            ->setTimeFromTimeString(
                                                                $schedule->departure_time->format('H:i:s'),
                                                            );
                                                        echo $tomorrowDeparture->format('d M Y H:i') . ' (WIB)';
                                                    }
                                                @endphp
                                            @else
                                                {{ $schedule->getDepartureTimeWIB()->format('d M Y H:i') }} (WIB)
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->getArrivalTimeWIB()->format('H:i') }} (WIB)
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($schedule->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="text-sm text-gray-900">
                                                {{ $schedule->getAvailableSeatsCount() }}/{{ $schedule->bus->capacity ?? 0 }}
                                                kursi
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-blue-600 h-2 rounded-full"
                                                    style="width: {{ ($schedule->getAvailableSeatsCount() / max(1, $schedule->bus->capacity ?? 1)) * 100 }}%">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if ($schedule->status === 'active')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @elseif($schedule->status === 'cancelled')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Tertunda
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-row gap-2 action-buttons">
                                                <a href="{{ route('admin.schedule-management.show', $schedule) }}"
                                                    class="view-icon" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                                    class="edit-icon" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if ($schedule->hasDeparted() && $schedule->is_daily)
                                                    <form id="create-next-day-form-{{ $schedule->id }}"
                                                        action="{{ route('admin.schedules.create-next-day', $schedule) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="button"
                                                            class="text-green-600 hover:text-green-900"
                                                            onclick="handleDelete('create-next-day-form-{{ $schedule->id }}', 'Buat Jadwal Hari Berikutnya?', 'Apakah Anda yakin ingin membuat jadwal untuk hari berikutnya berdasarkan jadwal ini?')"
                                                            title="Buat Jadwal Hari Berikutnya">
                                                            <i class="fas fa-calendar-plus"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form id="delete-form-{{ $schedule->id }}"
                                                    action="{{ route('admin.schedules.destroy', $schedule) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-icon"
                                                        onclick="handleDelete('delete-form-{{ $schedule->id }}', 'Hapus Jadwal?', 'Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.')"
                                                        title="Hapus">
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
