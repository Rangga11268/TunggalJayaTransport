@extends('frontend.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
        <div class="flex justify-between items-center mb-6 flex-col md:flex-row gap-4">
            <h1 class="text-3xl font-bold">Jadwal Lengkap</h1>
            <div class="flex items-center space-x-2 flex-wrap justify-center">
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mb-2 md:mb-0">
                    <i class="fas fa-sync-alt mr-1"></i>Atur Ulang Harian
                </span>
            </div>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 mobile-booking-card">
            <h2 class="text-xl font-bold mb-4">Cari Jadwal</h2>
            <form method="GET" action="{{ route('frontend.booking.schedules') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 mobile-search-form">
                <div class="mobile-form-group">
                    <label for="origin" class="block text-sm font-medium text-gray-700 mobile-form-label">Asal</label>
                    <select id="origin" name="origin"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mobile-select">
                        <option value="">Semua Asal</option>
                        @foreach ($origins as $originOption)
                            <option value="{{ $originOption }}"
                                {{ request()->get('origin') == $originOption ? 'selected' : '' }}>{{ $originOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mobile-form-group">
                    <label for="destination"
                        class="block text-sm font-medium text-gray-700 mobile-form-label">Tujuan</label>
                    <select id="destination" name="destination"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mobile-select">
                        <option value="">Semua Tujuan</option>
                        @foreach ($destinations as $destinationOption)
                            <option value="{{ $destinationOption }}"
                                {{ request()->get('destination') == $destinationOption ? 'selected' : '' }}>
                                {{ $destinationOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mobile-form-group">
                    <label for="date" class="block text-sm font-medium text-gray-700 mobile-form-label">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ request()->get('date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mobile-input">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mobile-btn-full">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Schedule Legend -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-4 mb-6 mobile-legend">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center mb-2 md:mb-0">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-sm">Jadwal Harian (Tanggal spesifik)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                    <span class="text-sm">Jadwal Harian Berulang (Tersedia setiap hari)</span>
                </div>
            </div>
        </div>

        <!-- Schedule Results -->
        @if ($schedules->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mobile-booking-card">
                <!-- Mobile responsive approach - only cards for mobile, table for desktop -->
                <div class="hidden md:block">
                    <!-- Desktop table -->
                    <div class="mobile-table-responsive">
                        <table class="min-w-full divide-y divide-gray-200 mobile-table mobile-booking-table">
                            <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Jadwal
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Rute</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Bus</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                        Ketersediaan</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Harga</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($schedules as $schedule)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap" data-label="Schedule">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $schedule->is_daily ? 'bg-yellow-100' : 'bg-blue-100' }}">
                                                    <i
                                                        class="fas fa-{{ $schedule->is_daily ? 'sync-alt' : 'clock' }} text-{{ $schedule->is_daily ? 'yellow-600' : 'blue' }}-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if(request()->get('date'))
                                                            {{ $schedule->getActualDepartureTime(\Carbon\Carbon::parse(request()->get('date')))->format('H:i') }}
                                                            <span class="text-gray-500 mx-1">→</span>
                                                            {{ $schedule->getActualArrivalTime(\Carbon\Carbon::parse(request()->get('date')))->format('H:i') }}
                                                        @else
                                                            {{ $schedule->getActualDepartureTime()->format('H:i') }}
                                                            <span class="text-gray-500 mx-1">→</span>
                                                            {{ $schedule->getActualArrivalTime()->format('H:i') }}
                                                        @endif
                                                        <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        @if(request()->get('date'))
                                                            {{ \Carbon\Carbon::parse(request()->get('date'))->format('M j') }}
                                                        @else
                                                            {{ $schedule->getActualDepartureTime()->format('M j') }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap" data-label="Route">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->route->origin }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $schedule->route->destination }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap" data-label="Bus">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $schedule->bus->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $schedule->bus->plate_number }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap" data-label="Availability">
                                            @if ($schedule->hasDeparted())
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>Sudah Berangkat
                                                </span>
                                            @else
                                                <div class="text-sm text-gray-900">
                                                    @if(request()->get('date'))
                                                        {{ $schedule->getAvailableSeatsCount(\Carbon\Carbon::parse(request()->get('date'))) }} /
                                                    @else
                                                        {{ $schedule->getAvailableSeatsCount() }} /
                                                    @endif
                                                    {{ $schedule->bus->capacity }} kursi</div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-blue-600 h-2 rounded-full"
                                                        @if(request()->get('date'))
                                                            style="width: {{ ($schedule->getAvailableSeatsCount(\Carbon\Carbon::parse(request()->get('date'))) / max(1, $schedule->bus->capacity)) * 100 }}%">
                                                        @else
                                                            style="width: {{ ($schedule->getAvailableSeatsCount() / max(1, $schedule->bus->capacity)) * 100 }}%">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"
                                            data-label="Price">
                                            Rp. {{ number_format($schedule->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium" data-label="Action">
                                            @if ($schedule->hasDeparted())
                                                <span class="text-gray-400">Tidak Tersedia</span>
                                            @elseif($schedule->getAvailableSeatsCount() > 0)
                                                <a href="{{ route('frontend.booking.show', $schedule->id) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    Pesan Sekarang
                                                </a>
                                            @else
                                                <span class="text-gray-400">Penuh</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile cards only -->
                <div class="md:hidden">
                    @foreach ($schedules as $schedule)
                        <div class="border-b border-gray-200 last:border-b-0">
                            <div class="p-4">
                                <!-- Header with schedule type icon -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $schedule->is_daily ? 'bg-yellow-100' : 'bg-blue-100' }}">
                                            <i
                                                class="fas fa-{{ $schedule->is_daily ? 'sync-alt' : 'clock' }} text-{{ $schedule->is_daily ? 'yellow-600' : 'blue' }}-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if(request()->get('date'))
                                                    {{ $schedule->getActualDepartureTime(\Carbon\Carbon::parse(request()->get('date')))->format('H:i') }} →
                                                    {{ $schedule->getActualArrivalTime(\Carbon\Carbon::parse(request()->get('date')))->format('H:i') }}
                                                @else
                                                    {{ $schedule->getActualDepartureTime()->format('H:i') }} →
                                                    {{ $schedule->getActualArrivalTime()->format('H:i') }}
                                                @endif
                                                <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @if(request()->get('date'))
                                                    {{ \Carbon\Carbon::parse(request()->get('date'))->format('M j') }}
                                                @else
                                                    {{ $schedule->getActualDepartureTime()->format('M j') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-gray-900">Rp.
                                            {{ number_format($schedule->price, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <!-- Route info -->
                                <div class="flex justify-between items-center mb-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->route->origin }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $schedule->route->destination }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">{{ $schedule->bus->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $schedule->bus->plate_number }}</div>
                                    </div>
                                </div>

                                <!-- Availability and action -->
                                <div class="mb-2">
                                    @if ($schedule->hasDeparted())
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Sudah Berangkat
                                        </span>
                                    @else
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="text-xs text-gray-900">
                                                {{ $schedule->getAvailableSeatsCount() }}/{{ $schedule->bus->capacity }}
                                                kursi
                                            </div>
                                            @if ($schedule->getAvailableSeatsCount() > 0 && !$schedule->hasDeparted())
                                                <a href="{{ route('frontend.booking.show', ['id' => $schedule->id, 'date' => request()->get('date')]) }}"
                                                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded font-medium">
                                                    Pesan Sekarang
                                                </a>
                                            @elseif($schedule->getAvailableSeatsCount() == 0)
                                                <span class="text-xs text-gray-400">Penuh</span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                @if(request()->get('date'))
                                                    style="width: {{ ($schedule->getAvailableSeatsCount(\Carbon\Carbon::parse(request()->get('date'))) / max(1, $schedule->bus->capacity)) * 100 }}%"
                                                @else
                                                    style="width: {{ ($schedule->getAvailableSeatsCount() / max(1, $schedule->bus->capacity)) * 100 }}%"
                                                @endif
                                            >
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    {{ $schedules->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center mobile-booking-card">
                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada jadwal ditemukan</h3>
                <p class="text-gray-500">Tidak ada jadwal tersedia yang cocok dengan kriteria pencarian Anda.</p>
            </div>
        @endif
    </div>
@endsection
