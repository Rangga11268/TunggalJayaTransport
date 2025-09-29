@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-8 sm:mb-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Tentang Kami</h1>
        <p class="text-lg sm:text-xl text-gray-600 max-w-2xl sm:max-w-3xl mx-auto">Pelajari lebih lanjut tentang Tunggal Jaya Transport, kisah kami, nilai-nilai, dan komitmen kami dalam menyediakan layanan transportasi yang luar biasa</p>
    </div>
    
    <!-- Profil Perusahaan -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-12">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Kisah Kami</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-xs sm:text-sm font-bold">
                <i class="fas fa-history mr-2"></i>Sejak 2005
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-6 items-center">
            <div class="lg:w-2/5 w-full">
                <div class="relative">
                    <div class="rounded-xl overflow-hidden w-full aspect-video">
                        <video 
                            class="w-full h-full object-cover"
                            autoplay 
                            muted 
                            loop 
                            playsinline
                            preload="metadata">
                            <source src="{{ asset('video/cinematiac 2.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="absolute -bottom-4 -right-4 sm:-bottom-6 sm:-right-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-3 sm:p-6 rounded-xl shadow-lg">
                        <div class="text-2xl sm:text-3xl font-bold">18+</div>
                        <div class="text-xs sm:text-sm">Years Experience</div>
                    </div>
                </div>
            </div>
            <div class="lg:w-3/5 w-full">
                <p class="text-gray-700 text-base sm:text-lg mb-4">
                    Tunggal Jaya Transport telah menyediakan layanan transportasi yang andal dan nyaman sejak 2005. 
                    Dengan pengalaman lebih dari 18 tahun, kami telah berkembang menjadi salah satu perusahaan bus 
                    yang paling dipercaya di kawasan ini.
                </p>
                <p class="text-gray-700 text-base sm:text-lg mb-4">
                    Komitmen kami terhadap keselamatan, ketepatan waktu, dan kepuasan pelanggan telah menjadikan 
                    kami pilihan utama bagi ribuan pelancong setiap harinya. Kami percaya dalam memberikan lebih 
                    dari sekadar transportasi - kami memberikan pengalaman.
                </p>
                <p class="text-gray-700 text-base sm:text-lg mb-6">
                    Kami terus berinvestasi dalam armada dan pelatihan staf kami untuk memastikan 
                    memberikan layanan terbaik bagi pelanggan kami yang terhormat. Bus-bus modern kami dilengkapi 
                    dengan fasilitas terbaru untuk kenyamanan dan keselamatan Anda.
                </p>
                <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                    <div class="flex items-center bg-white p-3 rounded-lg shadow-sm mb-2 sm:mb-0">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-bus text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm sm:text-base">Armada Modern</div>
                            <div class="text-xs sm:text-sm text-gray-600">Bus yang terawat baik</div>
                        </div>
                    </div>
                    <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm sm:text-base">Staf Profesional</div>
                            <div class="text-xs sm:text-sm text-gray-600">Sopir & awak yang terlatih</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visi dan Misi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                    <i class="fas fa-eye text-green-600 text-lg sm:text-xl"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Visi Kami</h2>
            </div>
            <p class="text-gray-700 text-base sm:text-lg">
                Menjadi penyedia transportasi terkemuka di kawasan ini, dikenal karena komitmen kami terhadap 
                keselamatan, kenyamanan, dan tanggung jawab lingkungan. Kami berusaha menghubungkan komunitas 
                sambil menjaga planet kita untuk generasi mendatang.
            </p>
            <div class="mt-4 flex items-center text-green-600">
                <i class="fas fa-quote-left text-xl sm:text-3xl mr-2"></i>
                <p class="italic text-sm sm:text-base">Menghubungkan orang, menghubungkan masa depan</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                    <i class="fas fa-bullseye text-purple-600 text-lg sm:text-xl"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Misi Kami</h2>
            </div>
            <ul class="space-y-3">
                <li class="flex items-start">
                    <div class="bg-purple-100 p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3 mt-1">
                        <i class="fas fa-check text-purple-600 text-sm"></i>
                    </div>
                    <span class="text-gray-700 text-sm sm:text-lg">Menyediakan layanan transportasi yang aman dan andal</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3 mt-1">
                        <i class="fas fa-check text-purple-600 text-sm"></i>
                    </div>
                    <span class="text-gray-700 text-sm sm:text-lg">Memastikan kenyamanan dan kepuasan penumpang</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3 mt-1">
                        <i class="fas fa-check text-purple-600 text-sm"></i>
                    </div>
                    <span class="text-gray-700 text-sm sm:text-lg">Terus meningkatkan armada dan fasilitas kami</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3 mt-1">
                        <i class="fas fa-check text-purple-600 text-sm"></i>
                    </div>
                    <span class="text-gray-700 text-sm sm:text-lg">Berkontribusi pada solusi transportasi yang berkelanjutan</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3 mt-1">
                        <i class="fas fa-check text-purple-600 text-sm"></i>
                    </div>
                    <span class="text-gray-700 text-sm sm:text-lg">Menjaga standar profesionalitas tertinggi</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bagian Statistik -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-12 text-white">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-8">Dalam Angka</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            <div class="bg-white bg-opacity-10 p-4 sm:p-6 rounded-xl mb-3 sm:mb-0">
                <div class="text-2xl sm:text-4xl font-bold mb-1">50+</div>
                <div class="text-sm sm:text-lg">Destinasi</div>
            </div>
            <div class="bg-white bg-opacity-10 p-4 sm:p-6 rounded-xl mb-3 sm:mb-0">
                <div class="text-2xl sm:text-4xl font-bold mb-1">100+</div>
                <div class="text-sm sm:text-lg">Bus Modern</div>
            </div>
            <div class="bg-white bg-opacity-10 p-4 sm:p-6 rounded-xl mb-3 sm:mb-0">
                <div class="text-2xl sm:text-4xl font-bold mb-1">200+</div>
                <div class="text-sm sm:text-lg">Staf Profesional</div>
            </div>
            <div class="bg-white bg-opacity-10 p-4 sm:p-6 rounded-xl">
                <div class="text-2xl sm:text-4xl font-bold mb-1">1J+</div>
                <div class="text-sm sm:text-lg">Penumpang Bahagia</div>
            </div>
        </div>
    </div>

    <!-- Bagian FAQ -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Pertanyaan Umum</h2>
            <div class="bg-gray-200 text-gray-800 px-3 py-1.5 rounded-full text-xs sm:text-sm font-bold">
                <i class="fas fa-question-circle mr-2"></i>FAQ
            </div>
        </div>
        <div x-data="{ open: null }" class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 1 ? null : 1" class="flex justify-between items-center w-full text-left p-4 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-base sm:text-lg">Bagaimana cara memesan tiket?</span>
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 1 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 1" class="px-4 pb-4 text-gray-600" x-collapse>
                    <p class="text-sm sm:text-base">Anda dapat memesan tiket melalui website kami, aplikasi mobile, atau dengan mengunjungi salah satu kantor penjualan tiket kami. Sistem pemesanan online kami tersedia 24/7 untuk kenyamanan Anda.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 2 ? null : 2" class="flex justify-between items-center w-full text-left p-4 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-base sm:text-lg">Metode pembayaran apa yang Anda terima?</span>
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 2 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 2" class="px-4 pb-4 text-gray-600" x-collapse>
                    <p class="text-sm sm:text-base">Kami menerima uang tunai, kartu kredit, kartu debit, dan pembayaran digital melalui aplikasi mobile kami. Semua kartu kredit utama termasuk Visa, Mastercard, dan American Express diterima.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 3 ? null : 3" class="flex justify-between items-center w-full text-left p-4 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-base sm:text-lg">Bisakah saya membatalkan atau mengganti pemesanan saya?</span>
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 3 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 3" class="px-4 pb-4 text-gray-600" x-collapse>
                    <p class="text-sm sm:text-base">Ya, Anda dapat membatalkan atau mengganti pemesanan Anda hingga 2 jam sebelum keberangkatan, tergantung pada kebijakan pembatalan kami. Pembatalan yang dilakukan kurang dari 24 jam sebelum keberangkatan mungkin dikenakan biaya.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 4 ? null : 4" class="flex justify-between items-center w-full text-left p-4 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-base sm:text-lg">Fasilitas apa yang tersedia di bus Anda?</span>
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 4 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 4" class="px-4 pb-4 text-gray-600" x-collapse>
                    <p class="text-sm sm:text-base">Bus-bus kami dilengkapi dengan kursi nyaman yang dapat direbahkan, AC, toilet di dalam bus, WiFi gratis, port pengisian daya, dan sistem hiburan. Layanan premium mungkin termasuk makanan dan minuman.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection