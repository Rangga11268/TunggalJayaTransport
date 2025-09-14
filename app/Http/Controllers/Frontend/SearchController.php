<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return view('frontend.search.index', [
                'results' => [],
                'query' => '',
                'totalResults' => 0
            ]);
        }
        
        // Search in news articles
        $newsResults = NewsArticle::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->orWhere('excerpt', 'LIKE', "%{$query}%")
            ->where('is_published', true)
            ->get();
            
        // Search in routes
        $routeResults = BusRoute::where('name', 'LIKE', "%{$query}%")
            ->orWhere('origin', 'LIKE', "%{$query}%")
            ->orWhere('destination', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();
            
        // Combine all results
        $allResults = collect();
        
        foreach ($newsResults as $news) {
            $allResults->push([
                'type' => 'news',
                'title' => $news->title,
                'excerpt' => $news->excerpt,
                'published_at' => $news->published_at,
                'url' => route('frontend.news.show', $news->slug)
            ]);
        }
        
        foreach ($routeResults as $route) {
            $allResults->push([
                'type' => 'route',
                'title' => $route->name,
                'excerpt' => "Route from {$route->origin} to {$route->destination}",
                'published_at' => null,
                'url' => route('frontend.routes.show', $route->id)
            ]);
        }
        
        // Sort by relevance (this is a simple implementation)
        $results = $allResults->sortByDesc('published_at')->values();
        
        return view('frontend.search.index', [
            'results' => $results,
            'query' => $query,
            'totalResults' => $results->count()
        ]);
    }
}