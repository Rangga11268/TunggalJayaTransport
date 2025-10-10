<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lihat Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <div class="mt-2 text-sm text-gray-500">
                            {{ $user->email }}
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-2">Peran</h3>
                        @if($user->roles->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">Tidak ada peran yang ditetapkan.</p>
                        @endif
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-2">Informasi Akun</h3>
                        <dl class="grid grid-cols-1 gap-2">
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Anggota Sejak:</dt>
                                <dd class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="font-medium text-gray-500 w-32">Terakhir Diperbarui:</dt>
                                <dd class="text-gray-900">{{ $user->updated_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800">
                            ← Kembali ke Pengguna
                        </a>
                        <div>
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            @if($user->id != auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="event.preventDefault(); handleDelete('delete-form', 'Hapus Pengguna?', 'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>