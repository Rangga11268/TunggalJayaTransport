<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class NewsArticle extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'is_published',
        'published_at',
        'category_id',
        'author_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $appends = ['image_url', 'safe_content'];

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('cover')
            ?: $this->getFirstMediaUrl('featured_images')
            ?: $this->getFirstMediaUrl('default')
            ?: 'https://placehold.co/800x600?text=No+Image';
    }

    /**
     * Get sanitized content safe for v-html rendering
     * Applies allowlist of safe HTML tags
     */
    public function getSafeContentAttribute()
    {
        return strip_tags($this->content, '<p><br><b><i><u><strong><em><ul><ol><li><a><img>');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
