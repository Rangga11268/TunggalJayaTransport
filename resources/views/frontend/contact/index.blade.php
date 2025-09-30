@extends('frontend.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Terhubung Dengan Kami</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Punya pertanyaan? Tim kami ada disini untuk membantu anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Informasi Kontak -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Hubungi Kami</h2>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-headset mr-1"></i>Info Kontak
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start bg-white p-5 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-bold text-gray-800">Telepon</h3>
                            <p class="text-gray-600 mt-1">+62 21 1234 5678</p>
                            <p class="text-gray-500 text-sm mt-1">Senin-Jumat: 8 pagi - 8 malam, Sabtu-Minggu: 9 pagi - 5
                                sore</p>
                        </div>
                    </div>

                    <div class="flex items-start bg-white p-5 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-bold text-gray-800">Email</h3>
                            <p class="text-gray-600 mt-1">info@tunggaljayatransport.com</p>
                            <p class="text-gray-500 text-sm mt-1">Kami biasanya merespon dalam waktu 24 jam</p>
                        </div>
                    </div>

                    <div class="flex items-start bg-white p-5 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg font-bold text-gray-800">Kantor</h3>
                            <p class="text-gray-600 mt-1">Jl. Transportation No. 123, Jakarta 12345</p>
                            <p class="text-gray-500 text-sm mt-1">Kantor Pusat</p>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Cabang -->
                <h2 class="text-2xl font-bold text-gray-800 mt-10 mb-6">Lokasi Cabang</h2>
                <div
                    class="bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl w-full h-72 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-gray-500 text-5xl mb-3"></i>
                        <p class="text-2xl text-gray-500 font-bold">Peta Interaktif</p>
                        <p class="text-gray-600 mt-2">Lihat semua lokasi cabang kami</p>
                    </div>
                </div>

                <!-- Media Sosial -->
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="bg-blue-400 text-white p-3 rounded-full hover:bg-blue-500 transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#"
                            class="bg-pink-600 text-white p-3 rounded-full hover:bg-pink-700 transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="bg-blue-700 text-white p-3 rounded-full hover:bg-blue-800 transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulir Kontak -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-lg p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Kirim Pesan kepada Kami</h2>
                    <div class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-envelope mr-1"></i>Pesan
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('frontend.contact.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" id="name" name="name"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600"><i
                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600"><i
                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                        <input type="text" id="subject" name="subject"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600"><i
                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                        <textarea id="message" name="message" rows="5"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4"></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600"><i
                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                        </button>
                    </div>
                </form>

                <!-- Info Bantuan -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Butuh Bantuan Segera?</h3>
                    <p class="text-gray-600 mb-4">Untuk masalah mendesak, silakan hubungi layanan pelanggan kami 24/7:</p>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-phone-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-gray-800">+62 21 1234 5678</p>
                                <p class="text-gray-600">Layanan Pelanggan 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Opsi Kontak Tambahan -->
        <div class="mt-12 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Cara Lain untuk Terhubung</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm text-center hover:shadow-md transition duration-300">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Live Chat</h3>
                    <p class="text-gray-600 mb-3">Berbicara langsung dengan tim dukungan kami secara real-time</p>
                    <button class="text-green-600 hover:text-green-800 font-medium">
                        Mulai Chat <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm text-center hover:shadow-md transition duration-300">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-question-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Pusat Bantuan</h3>
                    <p class="text-gray-600 mb-3">Jelajahi FAQ dan panduan lengkap kami</p>
                    <button class="text-green-600 hover:text-green-800 font-medium">
                        Kunjungi Pusat Bantuan <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm text-center hover:shadow-md transition duration-300">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Aplikasi Mobile</h3>
                    <p class="text-gray-600 mb-3">Unduh aplikasi kami untuk akses yang lebih mudah</p>
                    <button class="text-green-600 hover:text-green-800 font-medium">
                        Unduh Aplikasi <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
