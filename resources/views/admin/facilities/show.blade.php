<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lihat Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">{{ $facility->name }}</h1>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Detail</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Nama:</dt>
                                    <dd class="text-gray-900">{{ $facility->name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-500 w-32">Ikon:</dt>
                                    <dd class="text-gray-900">
                                        @if($facility->icon)
                                            <i class="{{ $facility->icon }}"></i> ({{ $facility->icon }})
                                        @else
                                            <span class="text-gray-400">Tidak ada ikon</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium mb-2">Deskripsi</h3>
                            @if($facility->description)
                                <p class="text-gray-600">{{ $facility->description }}</p>
                            @else
                                <p class="text-gray-500 italic">Tidak ada deskripsi yang disediakan.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('admin.facilities.index') }}" class="text-gray-600 hover:text-gray-800">
                            ← Kembali ke Fasilitas
                        </a>
                        <div>
                            <a href="{{ route('admin.facilities.edit', $facility) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form id="delete-form" action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="event.preventDefault(); handleDelete('delete-form', 'Hapus Fasilitas?', 'Apakah Anda yakin ingin menghapus fasilitas ini? Tindakan ini tidak dapat dibatalkan.')">
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