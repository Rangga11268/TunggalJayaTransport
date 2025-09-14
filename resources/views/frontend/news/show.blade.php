@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <article class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>
        
        <div class="flex items-center text-gray-600 mb-6">
            <span>By {{ $article->author->name ?? 'Admin' }}</span>
            <span class="mx-2">•</span>
            <span>{{ $article->published_at->format('F j, Y') }}</span>
            @if($article->category)
            <span class="mx-2">•</span>
            <span class="bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">{{ $article->category->name }}</span>
            @endif
        </div>
        
        <div class="prose max-w-none">
            {!! $article->content !!}
        </div>
    </article>
    
    <!-- Back to News -->
    <div class="mb-8">
        <a href="{{ route('frontend.news.index') }}" class="inline-flex items-center text-blue-500 hover:underline">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to News
        </a>
    </div>
</div>
@endsection