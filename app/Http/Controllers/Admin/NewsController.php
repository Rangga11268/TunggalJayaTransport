<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = NewsArticle::latest()->paginate(10);
        return view('admin.news.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.news.create', compact('categories'));
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        return redirect()->route('admin.news.index')->with('create_success', 'Artikel berita berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = NewsArticle::findOrFail($id);
        return view('admin.news.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = NewsArticle::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.news.edit', compact('article', 'categories'));
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article = NewsArticle::findOrFail($id);
        $article->title = $request->title;
        $article->slug = $this->createUniqueSlug($request->title, $article->id);
        $article->content = $request->content;
        $article->excerpt = $request->excerpt;
        $article->category_id = $request->category_id;
        $article->is_published = $request->has('is_published');
        $article->save();

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete existing featured image if it exists
            $article->clearMediaCollection('featured_images');
            // Add new featured image
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_images');
        }

        return redirect()->route('admin.news.index')->with('update_success', 'Artikel berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.news.index')->with('delete_success', 'Artikel berita berhasil dihapus.');
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
