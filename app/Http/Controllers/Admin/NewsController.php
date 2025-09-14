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
        ]);

        $article = new NewsArticle();
        $article->title = $request->title;
        $article->slug = \Str::slug($request->title);
        $article->content = $request->content;
        $article->excerpt = $request->excerpt;
        $article->category_id = $request->category_id;
        $article->is_published = $request->has('is_published');
        $article->author_id = auth()->id();
        $article->save();

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully.');
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
        ]);

        $article = NewsArticle::findOrFail($id);
        $article->title = $request->title;
        $article->slug = \Str::slug($request->title);
        $article->content = $request->content;
        $article->excerpt = $request->excerpt;
        $article->category_id = $request->category_id;
        $article->is_published = $request->has('is_published');
        $article->save();

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully.');
    }
}
