<?php

namespace App\Models;

use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    protected $fillable = [
        'title', 'description', 'author', 'source',
        'category', 'url', 'image_url', 'published_at', 'content'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /* ----------------------------
       SCOPES: Filtering & Search
    ----------------------------- */

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['source'] ?? null, fn($q, $source) => $q->where('source', $source))
            ->when($filters['category'] ?? null, fn($q, $category) => $q->where('category', $category))
            ->when($filters['author'] ?? null, fn($q, $author) => $q->where('author', 'like', "%$author%"))
            ->when($filters['q'] ?? null, fn($q, $search) => $q->where('title', 'like', "%$search%"))
            ->when(
                isset($filters['date_from'], $filters['date_to']),
                fn($q) => $q->whereBetween('published_at', [$filters['date_from'], $filters['date_to']])
            );
    }

    /* ----------------------------
       ACCESSORS / MUTATORS
    ----------------------------- */

    // Example: Capitalize source name automatically
    public function getSourceAttribute($value)
    {
        return ucfirst($value);
    }

    // Example: Ensure all URLs are HTTPS
    /*public function setUrlAttribute($value)
    {
        $this->attributes['url'] = str_replace('http://', 'https://', $value);
    }*/

    /* ----------------------------
       REUSABLE METHODS
    ----------------------------- */
    /**
     * @throws \Exception
     */
    public static function storeOrUpdate(array $articleData)
    {
        $articleDate = $articleData['published_at'] ?? now();
        $date = new DateTime($articleDate);
        //format the published_at value to datetime
        $articleData['published_at'] = $date->format('Y-m-d H:i:s');
        return self::updateOrCreate(
            ['url' => $articleData['url']],
            $articleData
        );
    }
}
