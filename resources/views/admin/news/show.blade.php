<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lihat Artikel Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($article->getFirstMediaUrl('featured_images'))
                        <div class="mb-6">
                            <img src="{{ $article->getFirstMediaUrl('featured_images') }}" alt="Gambar Unggulan" class="w-full h-64 object-cover rounded-md">
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">{{ $article->title }}</h1>
                        <div class="mt-2 text-sm text-gray-500">
                            <span>Oleh {{ $article->author->name ?? 'Tidak Diketahui' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $article->created_at->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            @if($article->is_published)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Diterbitkan
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Draf
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
                            ← Kembali ke Berita
                        </a>
                        <div>
                            <a href="{{ route('admin.news.edit', $article) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form id="delete-form" action="{{ route('admin.news.destroy', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="event.preventDefault(); handleDelete('delete-form', 'Hapus Artikel?', 'Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>