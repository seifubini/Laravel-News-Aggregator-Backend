<?php

namespace App\Services\News;


class NewsApiService extends AbstractNewsService
{
    public function fetchArticles(): array
    {
        $data = $this->get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => config('services.newsapi.key'),
            'country' => 'us',
            'pageSize' => 10,
        ]);

        return collect($data['articles'] ?? [])->map(function ($item) {
            return [
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'author' => $item['author'] ?? null,
                'source' => $item['source']['name'] ?? 'NewsAPI',
                'category' => 'general',
                'url' => $item['url'],
                'image_url' => $item['urlToImage'] ?? null,
                'published_at' => $item['publishedAt'] ?? now(),
                'content' => $item['content'] ?? null,
            ];
        })->toArray();

    }
}
