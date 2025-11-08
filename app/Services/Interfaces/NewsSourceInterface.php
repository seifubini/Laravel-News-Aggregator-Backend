<?php

namespace App\Services\Interfaces;

interface NewsSourceInterface
{
    /**
     * Fetch a list of articles from the external API.
     *
     * @return array Each article should be an associative array with keys:
     *               'title', 'description', 'author', 'source',
     *               'category', 'url', 'image_url', 'published_at', 'content'
     */
    public function fetchArticles(): array;
}
