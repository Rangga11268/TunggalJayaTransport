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
            
        return view('frontend.news.show', compact('article'));
    }
}