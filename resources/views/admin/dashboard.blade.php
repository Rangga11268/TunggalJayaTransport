<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dasbor Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-blue-500">{{ $totalBookings }}</div>
                            <div class="ml-4 text-gray-500">Total Pemesanan</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-green-500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                            <div class="ml-4 text-gray-500">Pendapatan</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-yellow-500">{{ $totalSchedules }}</div>
                            <div class="ml-4 text-gray-500">Rute Aktif</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-purple-500">{{ $totalUsers }}</div>
                            <div class="ml-4 text-gray-500">Pengguna Terdaftar</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Aktivitas Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentBookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Pemesanan baru #{{ $booking->booking_code }} untuk {{ $booking->schedule->route->origin }} ke {{ $booking->schedule->route->destination }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->passenger_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada aktivitas terbaru ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aksi Cepat -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 table-container">
                    <h3 class="text-lg font-bold mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.bookings.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded text-center">
                            Buat Pemesanan
                        </a>
                        <a href="{{ route('admin.news.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded text-center">
                            Tambah Berita
                        </a>
                        <a href="{{ route('admin.buses.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Bus
                        </a>
                        <a href="{{ route('admin.routes.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Rute
                        </a>
                        <a href="{{ route('admin.schedules.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Jadwal
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Pemesanan
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Pengguna
                        </a>
                        <a href="{{ route('admin.facilities.index') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Fasilitas
                        </a>
                        <a href="{{ route('admin.drivers.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded text-center">
                            Atur Sopir
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-6 rounded text-center">
                            Lihat Laporan
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-4 px-6 rounded text-center">
                            Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>