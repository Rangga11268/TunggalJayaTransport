@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <h1 class="text-3xl font-bold mb-6">Search Results</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('frontend.search.index') }}" class="flex">
            <input type="text" name="q" placeholder="Search..." class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request()->get('q') }}">
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-medium rounded-r-md hover:bg-blue-600">
                Search
            </button>
        </form>
    </div>
    
    <div class="mb-6">
        <p class="text-gray-600">Found {{ $totalResults }} results for "{{ $query }}"</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($results as $result)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">
                    <a href="{{ $result['url'] }}" class="text-blue-500 hover:underline">{{ $result['title'] }}</a>
                </h3>
                @if($result['type'] === 'news')
                <div class="text-sm text-gray-500 mb-3">News • {{ $result['published_at'] ? $result['published_at']->format('F j, Y') : 'N/A' }}</div>
                @else
                <div class="text-sm text-gray-500 mb-3">Route</div>
                @endif
                <p class="text-gray-600">{{ $result['excerpt'] }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600">No results found for "{{ request()->get('q') }}". Please try different keywords.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination would go here if we had pagination -->
</div>
@endsection