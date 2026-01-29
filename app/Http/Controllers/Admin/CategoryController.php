<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    
    public function index(Request $request)
    {
        $categories = Category::withCount('articles')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search'])
        ]);
    }

    
    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = \Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('Admin/Categories/Edit', compact('category'));
    }

    
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->name = $request->name;
        if ($category->name !== $request->name) {
             $category->slug = \Str::slug($request->name);
        }
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->save();

         return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has articles
        if ($category->articles()->count() > 0) {
             return redirect()->back()->with('error', 'Tidak dapat menghapus kategori karena masih memiliki artikel.');
        }
        
        // Check if category has children
        if ($category->children()->count() > 0) {
             return redirect()->back()->with('error', 'Tidak dapat menghapus kategori karena masih memiliki subkategori.');
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}