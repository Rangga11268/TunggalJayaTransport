@extends('frontend.layouts.app')

@use(Illuminate\Support\Str)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <article class="bg-white rounded-xl shadow-lg overflow-hidden mb-10">
        @if($article->getFirstMediaUrl('featured_images'))
            <div class="relative">
                <img src="{{ $article->getFirstMediaUrl('featured_images') }}" alt="{{ $article->title }}" class="w-full h-96 object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <h1 class="text-4xl font-bold text-white mb-4">{{ $article->title }}</h1>
                    <div class="flex flex-wrap items-center text-gray-200">
                        <div class="flex items-center mr-6 mb-2">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 mr-3"></div>
                            <div>
                                <p class="font-medium">Oleh {{ $article->author->name ?? 'Admin' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center mr-6 mb-2">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <span>{{ $article->published_at ? $article->published_at->format('F j, Y') : ($article->created_at ? $article->created_at->format('F j, Y') : 'N/A') }}</span>
                        </div>
                        @if($article->category)
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tag mr-2"></i>
                            <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full">{{ $article->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 p-8">
                <h1 class="text-4xl font-bold text-white mb-6">{{ $article->title }}</h1>
                <div class="flex flex-wrap items-center text-gray-300">
                    <div class="flex items-center mr-6 mb-2">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 mr-3"></div>
                        <div>
                            <p class="font-medium">Oleh {{ $article->author->name ?? 'Admin' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center mr-6 mb-2">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <span>{{ $article->published_at ? $article->published_at->format('F j, Y') : ($article->created_at ? $article->created_at->format('F j, Y') : 'N/A') }}</span>
                    </div>
                    @if($article->category)
                    <div class="flex items-center mb-2">
                        <i class="fas fa-tag mr-2"></i>
                        <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full">{{ $article->category->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        @endif
        
        <div class="p-8">
            <div class="prose max-w-none">
                {!! $article->content !!}
            </div>
        </div>
    </article>
    
    <!-- Artikel Terkait -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-8 mb-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Artikel Terkait</h2>
        @if($relatedArticles && $relatedArticles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedArticles as $relatedArticle)
            <div class="bg-white rounded-lg shadow-sm p-6">
                @if($relatedArticle->getFirstMediaUrl('featured_images'))
                    <img src="{{ $relatedArticle->getFirstMediaUrl('featured_images') }}" alt="{{ $relatedArticle->title }}" class="w-full h-32 object-cover rounded-lg mb-4">
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32 mb-4"></div>
                @endif
                <h3 class="text-lg font-bold mb-2">
                    <a href="{{ route('frontend.news.show', $relatedArticle->slug) }}" class="hover:text-blue-600">
                        {{ Str::limit($relatedArticle->title, 50) }}
                    </a>
                </h3>
                <p class="text-gray-600 text-sm mb-3">
                    {{ Str::limit(strip_tags($relatedArticle->excerpt ?: $relatedArticle->content), 100) }}
                </p>
                <a href="{{ route('frontend.news.show', $relatedArticle->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                    Baca Selengkapnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Artikel Terkait</h3>
            <p class="text-gray-500">Kami tidak dapat menemukan artikel terkait saat ini. Periksa kembali nanti untuk konten lainnya.</p>
        </div>
        @endif
    </div>
    
    <!-- Kembali ke Berita -->
    <div class="mb-12">
        <a href="{{ route('frontend.news.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold rounded-lg transition duration-300 shadow-lg transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Berita
        </a>
    </div>
</div>
@endsection