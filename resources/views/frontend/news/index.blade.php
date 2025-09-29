@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Berita & Pembaruan</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Tetap terinformasi dengan berita terbaru, pengumuman, dan pembaruan dari Tunggal Jaya Transport</p>
    </div>
    
    <!-- Filter Kategori -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Filter berdasarkan Kategori</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-filter mr-1"></i>{{ $articles->count() }} Artikel
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('frontend.news.index') }}" class="px-5 py-2 {{ !request()->get('category') ? 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-md' : 'bg-white text-gray-800 hover:bg-gray-100 shadow-sm' }} rounded-full font-medium transition duration-300">
                Semua
            </a>
            @isset($categories)
                @foreach($categories as $category)
                    <a href="{{ route('frontend.news.index', ['category' => $category->id]) }}" class="px-5 py-2 {{ request()->get('category') == $category->id ? 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-md' : 'bg-white text-gray-800 hover:bg-gray-100 shadow-sm' }} rounded-full font-medium transition duration-300">
                        {{ $category->name }}
                    </a>
                @endforeach
            @endisset
        </div>
    </div>

    <!-- News List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @forelse($articles as $article)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            @if($article->getFirstMediaUrl('featured_images'))
                <div class="relative">
                    <img src="{{ $article->getFirstMediaUrl('featured_images') }}" alt="{{ $article->title }}" class="w-full h-56 object-cover">
                    @if($article->category)
                    <div class="absolute top-4 left-4">
                        <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full font-medium">{{ $article->category->name }}</span>
                    </div>
                    @endif
                </div>
            @else
                <div class="relative bg-gradient-to-r from-gray-200 to-gray-300 w-full h-56 flex items-center justify-center">
                    <i class="fas fa-newspaper text-gray-500 text-5xl"></i>
                    @if($article->category)
                    <div class="absolute top-4 left-4">
                        <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full font-medium">{{ $article->category->name }}</span>
                    </div>
                    @endif
                </div>
            @endif
            <div class="p-6">
                <div class="flex items-center text-gray-500 text-sm mb-3">
                    <i class="far fa-calendar mr-2"></i>
                    <span>{{ $article->published_at ? $article->published_at->format('F j, Y') : 'N/A' }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">{{ $article->title }}</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}</p>
                <a href="{{ route('frontend.news.show', $article->slug) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    Baca Selengkapnya 
                    <i class="fas fa-chevron-right ml-2 text-sm"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-sm p-12 text-center">
                <div class="text-gray-400 text-6xl mb-6">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Tidak Ada Artikel Berita Ditemukan</h3>
                <p class="text-gray-600 max-w-md mx-auto">Saat ini tidak ada artikel berita yang tersedia. Silakan periksa kembali nanti untuk pembaruan.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="mt-10 flex justify-center">
        <div class="bg-white rounded-lg shadow-md p-4">
            {{ $articles->links() }}
        </div>
    </div>
    @endif

    <!-- Berlangganan Newsletter -->
    <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl shadow-lg p-8 mt-12">
        <div class="max-w-2xl mx-auto text-center">
            <div class="text-purple-500 text-4xl mb-4">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-3">Berlangganan Newsletter Kami</h2>
            <p class="text-gray-600 mb-6">Tetap terbaru dengan berita terbaru, promosi, dan tips perjalanan kami. Tidak ada spam, hanya informasi yang berguna.</p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                <input type="email" placeholder="Alamat email Anda" class="flex-grow px-5 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-sm">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-violet-700 text-white font-bold rounded-lg hover:from-purple-700 hover:to-violet-800 shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-paper-plane mr-2"></i>Berlangganan
                </button>
            </form>
            <p class="text-gray-500 text-sm mt-4">Dengan berlangganan, Anda menyetujui Kebijakan Privasi kami dan menyetujui untuk menerima pembaruan.</p>
        </div>
    </div>
</div>
@endsection