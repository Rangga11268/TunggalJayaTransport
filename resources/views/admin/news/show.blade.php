<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View News Article') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">{{ $article->title }}</h1>
                        <div class="mt-2 text-sm text-gray-500">
                            <span>By {{ $article->author->name ?? 'Unknown' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $article->created_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            @if($article->is_published)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($article->excerpt)
                        <div class="mb-6">
                            <p class="text-lg italic text-gray-600">{{ $article->excerpt }}</p>
                        </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                    
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('admin.news.index') }}" class="text-gray-600 hover:text-gray-800">
                            ← Back to News
                        </a>
                        <div>
                            <a href="{{ route('admin.news.edit', $article) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form action="{{ route('admin.news.destroy', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this article?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>