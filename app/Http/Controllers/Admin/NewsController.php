<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{

    public function index(Request $request)
    {
        $articles = NewsArticle::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'articles' => $articles
            ]);
        }

        return Inertia::render('Admin/News/Index', [
            'articles' => $articles,
            'filters' => $request->only(['search'])
        ]);
    }


    public function create()
    {
        $categories = \App\Models\Category::all();
        return Inertia::render('Admin/News/Create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $article = new NewsArticle();
        $article->title = $request->title;
        $article->slug = $this->createUniqueSlug($request->title);

        $article->content = strip_tags($request->input('content'), '<p><br><b><i><u><strong><em><ul><ol><li><a><img>');

        $article->excerpt = $request->excerpt;
        $article->category_id = $request->category_id;
        $article->is_published = $request->has('is_published');
        $article->author_id = Auth::id();
        $article->save();

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_images');
        }

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil dibuat.');
    }


    public function show(string $id)
    {
        $article = NewsArticle::with('category')->findOrFail($id);
        return Inertia::render('Admin/News/Edit', [
            'article' => $article,
            'categories' => \App\Models\Category::all(),
            'readonly' => true // Optional flag if we want to reuse Edit component
        ]);
    }


    public function edit(string $id)
    {
        $article = NewsArticle::with('media')->findOrFail($id);
        // Append image_url manually or rely on accessor if appended
        $article->append('image_url');

        $categories = \App\Models\Category::all();
        return Inertia::render('Admin/News/Edit', compact('article', 'categories'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $article = NewsArticle::findOrFail($id);
        $article->title = $request->title;
        if ($article->title !== $request->title) {
            $article->slug = $this->createUniqueSlug($request->title, $article->id);
        }

        $article->fill(['content' => strip_tags($request->input('content'), '<p><br><b><i><u><strong><em><ul><ol><li><a><img>')]);

        $article->excerpt = $request->excerpt;
        $article->category_id = $request->category_id;
        $article->is_published = $request->has('is_published');
        $article->save();

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete existing featured image if it exists
            $article->clearMediaCollection('featured_images');
            $article->clearMediaCollection('cover'); // Clear old collection too just in case
            // Add new featured image
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_images');
        }

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil dihapus.');
    }


    private function createUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug exists, and if so, append a number to make it unique
        while (NewsArticle::where('slug', $slug)
            ->where('id', '!=', $excludeId)
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function uploadImage(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Kita gunakan model temporary atau NewsArticle terakhir untuk menyimpan media sementara
            // Di sini kita langsung simpan ke disk public/tinymce untuk kemudahan visual di editor
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/news_content', $filename);

            return response()->json([
                'location' => \Illuminate\Support\Facades\Storage::url($path)
            ]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih.'], 400);
        }
        NewsArticle::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' data berhasil dihapus.']);
    }
}
