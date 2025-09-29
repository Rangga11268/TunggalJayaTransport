@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Stepper Progress with Time Estimates -->
    <div class="mb-8 overflow-x-auto">
        <div class="flex items-center justify-center min-w-max">
            <div class="flex flex-col items-center">
                <div class="flex items-center text-blue-600 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-blue-600 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">1</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Jadwal</div>
                        <div class="text-xs text-gray-500">&lt; 1 menit</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex flex-auto border-t-2 border-gray-300 mx-1 md:mx-4"></div>
            <div class="md:hidden flex flex-col items-center mx-1">
                <div class="text-gray-300 text-lg">↓</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex items-center text-gray-500 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-gray-300 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">2</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Penumpang</div>
                        <div class="text-xs text-gray-500">&lt; 1 menit</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex flex-auto border-t-2 border-gray-300 mx-1 md:mx-4"></div>
            <div class="md:hidden flex flex-col items-center mx-1">
                <div class="text-gray-300 text-lg">↓</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex items-center text-gray-500 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-gray-300 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">3</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Kursi</div>
                        <div class="text-xs text-gray-500">1-2 menit</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex flex-auto border-t-2 border-gray-300 mx-1 md:mx-4"></div>
            <div class="md:hidden flex flex-col items-center mx-1">
                <div class="text-gray-300 text-lg">↓</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex items-center text-gray-500 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-gray-300 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">4</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Pembayaran</div>
                        <div class="text-xs text-gray-500">&lt; 1 menit</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Detail Pemesanan</h1>
        <p class="text-lg text-gray-600">Lengkapi informasi pemesanan Anda untuk memesan kursi</p>
    </div>
    
    <!-- Check if schedule has departed -->
    @if($schedule->hasDeparted())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>Jadwal ini sudah berangkat.</strong> 
                    Pemesanan tidak dapat dilakukan untuk jadwal yang sudah berangkat. 
                    Silakan pilih jadwal lain.
                </p>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('frontend.booking.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Pemilihan Jadwal
        </a>
    </div>
    
    @else
    
    
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Schedule Information -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-8 mobile-info-card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Informasi Jadwal</h2>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-clock mr-1"></i>{{ $schedule->route->formatted_duration }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-lg shadow-sm mobile-info-card">
                        <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Detail Rute</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Asal</p>
                                    <p class="font-medium">{{ $schedule->route->origin }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-map-pin text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tujuan</p>
                                    <p class="font-medium">{{ $schedule->route->destination }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-road text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jarak</p>
                                    <p class="font-medium">{{ $schedule->route->distance }} km</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-5 rounded-lg shadow-sm mobile-info-card">
                        <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Detail Jadwal</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-medium">
                                        @if(isset($selectedDate) && $selectedDate)
                                            {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                                        @else
                                            {{ $schedule->getActualDepartureTime(null)->format('d M Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-out-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Keberangkatan</p>
                                    <p class="font-medium">
                                        @if(isset($selectedDate) && $selectedDate)
                                            {{ $schedule->getActualDepartureTime(\Carbon\Carbon::parse($selectedDate))->format('H:i') }}
                                        @else
                                            {{ $schedule->getActualDepartureTime(null)->format('H:i') }}
                                        @endif
                                        <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kedatangan</p>
                                    <p class="font-medium">
                                        @if(isset($selectedDate) && $selectedDate)
                                            {{ $schedule->getActualArrivalTime(\Carbon\Carbon::parse($selectedDate))->format('H:i') }}
                                        @else
                                            {{ $schedule->getActualArrivalTime(null)->format('H:i') }}
                                        @endif
                                        <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-bus text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jenis Bus</p>
                                    <p class="font-medium">{{ $schedule->bus->bus_type ?? 'Standard' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-white p-5 rounded-lg shadow-sm mobile-info-card">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Kursi Tersedia</p>
                            <p class="text-xl font-bold text-gray-800">
                                @if(isset($selectedDate) && $selectedDate)
                                    {{ $schedule->getAvailableSeatsCount(\Carbon\Carbon::parse($selectedDate)) }} / {{ $schedule->bus->capacity }}
                                @else
                                    {{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Harga per kursi</p>
                            <p class="text-2xl font-bold text-blue-600">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 mt-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($schedule->getAvailableSeatsCount() / $schedule->bus->capacity) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Passenger Information -->
        <div>
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6 mobile-booking-card">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 mobile-info-card-title">Informasi Penumpang</h2>
                <form method="POST" action="{{ route('frontend.booking.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    @if(isset($selectedDate))
                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                    @endif
                    
                    <div class="mobile-form-group">
                        <label for="passenger_name" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Nama Lengkap</label>
                        <input type="text" id="passenger_name" name="passenger_name" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-form-control">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="passenger_email" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Alamat Email</label>
                        <input type="email" id="passenger_email" name="passenger_email" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-form-control">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="passenger_phone" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Nomor Telepon</label>
                        <input type="tel" id="passenger_phone" name="passenger_phone" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-form-control">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="number_of_seats" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Jumlah Kursi</label>
                        <select id="number_of_seats" name="number_of_seats" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-select">
                            <option value="1">1 Kursi (Rp. {{ number_format($schedule->price, 0, ',', '.') }})</option>
                            <option value="2">2 Kursi (Rp. {{ number_format($schedule->price * 2, 0, ',', '.') }})</option>
                            <option value="3">3 Kursi (Rp. {{ number_format($schedule->price * 3, 0, ',', '.') }})</option>
                            <option value="4">4 Kursi (Rp. {{ number_format($schedule->price * 4, 0, ',', '.') }})</option>
                            <option value="5">5 Kursi (Rp. {{ number_format($schedule->price * 5, 0, ',', '.') }})</option>
                        </select>
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="terms" required class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            Saya setuju dengan <a href="#" class="text-blue-600 hover:underline">Syarat dan Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>
                        </label>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-4 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg text-lg mobile-btn-full">
                            <i class="fas fa-credit-card mr-2"></i>Lanjut ke Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @endif
    
</div>
@endsection