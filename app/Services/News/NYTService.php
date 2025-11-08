<?php

namespace App\Services\News;

use Illuminate\Support\Arr;

class NYTService extends AbstractNewsService
{
    /**
     * Fetch and normalize top stories from New York Times Top Stories API.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        $data = $this->get('https://api.nytimes.com/svc/topstories/v2/home.json', [
            'api-key' => config('services.nyt.key'),
        ]);

        $results = Arr::get($data, 'results', []);

        return collect($results)->map(function ($item) {
            // pick first multimedia item if exists
            $image = null;
            if (!empty($item['multimedia']) && is_array($item['multimedia'])) {
                // prefer first non-null url
                foreach ($item['multimedia'] as $m) {
                    if (!empty($m['url'])) {
                        $image = $m['url'];
                        break;
                    }
                }
            }

            return [
                'title'        => $item['title'] ?? null,
                'description'  => $item['abstract'] ?? null,
                'author'       => $item['byline'] ?? null,
                'source'       => 'New York Times',
                'category'     => $item['section'] ?? 'general',
                'url'          => $item['url'] ?? null,
                'image_url'    => $image,
                'published_at' => $item['published_date'] ?? now(),
                'content'      => $item['abstract'] ?? null,
            ];
        })->toArray();
    }
}
