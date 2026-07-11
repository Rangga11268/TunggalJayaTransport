<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        $articles = NewsArticle::with('author:id,name')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->get(['id', 'title', 'slug', 'excerpt', 'image', 'author_id', 'published_at']);

        return response()->json([
            'success' => true,
            'data' => $articles,
        ]);
    }

    public function show($slug): JsonResponse
    {
        $article = NewsArticle::with('author:id,name')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }
}
