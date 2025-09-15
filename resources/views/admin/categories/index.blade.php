<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Categories</h3>
                        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Category
                        </button>
                    </div>

                    <!-- Categories Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $category->description ?? 'No description' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $category->parent ? $category->parent->name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description ?? '' }}', {{ $category->parent_id ?? 'null' }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button onclick="deleteCategory({{ $category->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Category</h3>
                <form id="createCategoryForm">
                    @csrf
                    <div class="mb-4">
                        <label for="create_name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="create_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <p id="create_name_error" class="text-red-500 text-xs italic mt-1 hidden"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="create_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="create_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="create_parent_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                        <select name="parent_id" id="create_parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">None</option>
                            @foreach(App\Models\Category::whereNull('parent_id')->get() as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="closeCreateModal()" class="text-gray-600 hover:text-gray-800">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Category</h3>
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-4">
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="edit_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <p id="edit_name_error" class="text-red-500 text-xs italic mt-1 hidden"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_parent_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                        <select name="parent_id" id="edit_parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">None</option>
                            @foreach(App\Models\Category::whereNull('parent_id')->get() as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="closeEditModal()" class="text-gray-600 hover:text-gray-800">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Create Modal Functions
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createCategoryForm').reset();
            document.getElementById('create_name_error').classList.add('hidden');
        }

        // Edit Modal Functions
        function openEditModal(id, name, description, parentId) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_parent_id').value = parentId || '';
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('edit_name_error').classList.add('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editCategoryForm').reset();
            document.getElementById('edit_name_error').classList.add('hidden');
        }

        // Create Category Form Submission
        document.getElementById('createCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const nameError = document.getElementById('create_name_error');
            nameError.classList.add('hidden');
            
            fetch("{{ route('admin.categories.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else if (data.errors) {
                    if (data.errors.name) {
                        nameError.textContent = data.errors.name[0];
                        nameError.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat membuat kategori.', 'error');
            });
        });

        // Edit Category Form Submission
        document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            const nameError = document.getElementById('edit_name_error');
            nameError.classList.add('hidden');
            
            fetch("{{ url('admin/categories') }}/" + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil Diperbarui!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else if (data.errors) {
                    if (data.errors.name) {
                        nameError.textContent = data.errors.name[0];
                        nameError.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui kategori.', 'error');
            });
        });

        // Delete Category
        function deleteCategory(id) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: 'Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ url('admin/categories') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil Dihapus!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                timer: 3000,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        } else if (data.error) {
                            Swal.fire('Error!', data.error, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus kategori.', 'error');
                    });
                }
            });
        }
    </script>
</x-app-layout>