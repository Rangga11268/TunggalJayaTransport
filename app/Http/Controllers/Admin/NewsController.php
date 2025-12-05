<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return Inertia::render('Admin/News/Index', [
            'articles' => $articles,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return Inertia::render('Admin/News/Create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
        $article->content = $request->content;
        $article->excerpt = $request->excerpt;
        $article->category_id = $request->category_id;
        $article->is_published = $request->has('is_published');
        $article->author_id = auth()->id();
        $article->save();

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_images');
        }

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Typically admin doesn't need a separate show page if not implemented in Vue yet, 
        // but we can reuse Edit or just redirect. Let's redirect to edit for now or leave as is if we want a preview.
        // For simplicity in this migration, I'll redirect to Edit or render a Show page if we build it.
        // Given the plan, I'll skip Show for now or make it render the frontend show? 
        // Let's just return the Edit view for now as 'Show' is often redundant in Admin unless it's a preview.
         $article = NewsArticle::with('category')->findOrFail($id);
         return Inertia::render('Admin/News/Edit', [
            'article' => $article,
            'categories' => \App\Models\Category::all(),
            'readonly' => true // Optional flag if we want to reuse Edit component
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = NewsArticle::with('media')->findOrFail($id);
        // Append image_url manually or rely on accessor if appended
        $article->append('image_url'); 
        
        $categories = \App\Models\Category::all();
        return Inertia::render('Admin/News/Edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        $article->content = $request->content;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil dihapus.');
    }

    /**
     * Create a unique slug for the article.
     */
    private function createUniqueSlug($title, $excludeId = null)
    {
        $slug = \Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug exists, and if so, append a number to make it unique
        while (NewsArticle::where('slug', $slug)
            ->where('id', '!=', $excludeId)
            ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
