<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->get('category');
        
        $query = NewsArticle::with(['category', 'author'])
            ->where('is_published', true);
            
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $articles = $query->latest()->paginate(9)->appends(['category' => $categoryId]);
        
        $categories = Category::all();
            
        return view('frontend.news.index', compact('articles', 'categories'));
    }
    
    public function show($slug)
    {
        $article = NewsArticle::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        // Fetch related articles from the same category, excluding the current article
        $relatedArticles = collect();
        if ($article->category_id) {
            $relatedArticles = NewsArticle::with(['category', 'author'])
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->where('is_published', true)
                ->limit(3)
                ->latest()
                ->get();
        }
        
        // If there are not enough articles in the same category, get from other categories
        if ($relatedArticles->count() < 3) {
            $additionalArticles = NewsArticle::with(['category', 'author'])
                ->where('id', '!=', $article->id)
                ->where('is_published', true)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->limit(3 - $relatedArticles->count())
                ->latest()
                ->get();
                
            $relatedArticles = $relatedArticles->concat($additionalArticles);
        }
            
        return view('frontend.news.show', compact('article', 'relatedArticles'));
    }
}