<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $articles = NewsArticle::where('is_published', true)
            ->latest()
            ->paginate(9);
            
        return view('frontend.news.index', compact('articles'));
    }
    
    public function show($slug)
    {
        $article = NewsArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        return view('frontend.news.show', compact('article'));
    }
}