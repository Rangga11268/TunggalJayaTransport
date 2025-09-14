@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">News & Updates</h1>
    
    <!-- Category Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Filter by Category</h2>
        <div class="flex flex-wrap gap-2">
            <a href="#" class="px-4 py-2 bg-blue-500 text-white rounded-full">All</a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300">Announcements</a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300">Promotions</a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300">Events</a>
        </div>
    </div>

    <!-- News List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($articles as $article)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
            <div class="p-4">
                <div class="text-sm text-gray-500 mb-2">{{ $article->published_at ? $article->published_at->format('F j, Y') : 'N/A' }}</div>
                <h3 class="text-xl font-bold mb-2">{{ $article->title }}</h3>
                <p class="text-gray-600">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}</p>
                <a href="{{ route('frontend.news.show', $article->slug) }}" class="text-blue-500 hover:underline mt-2 inline-block">Read More</a>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600">No news articles available at the moment.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $articles->links() }}
    </div>
    @endif

    <!-- Newsletter Subscription -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Subscribe to Our Newsletter</h2>
        <form class="flex flex-col sm:flex-row gap-4">
            <input type="email" placeholder="Your email address" class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600">
                Subscribe
            </button>
        </form>
    </div>
</div>
@endsection