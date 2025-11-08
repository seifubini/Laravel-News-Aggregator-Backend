<?php

namespace App\Services\News;

use Illuminate\Support\Arr;

class GuardianService extends AbstractNewsService
{
    /**
     * Fetch and normalize articles from The Guardian Content API.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        $data = $this->get('https://content.guardianapis.com/search', [
            'api-key' => config('services.guardian.key'),
            'show-fields' => 'trailText,body,thumbnail',
            'page-size' => 10,
        ]);

        $results = Arr::get($data, 'response.results', []);

        return collect($results)->map(function ($item) {
            $fields = $item['fields'] ?? [];

            return [
                'title'        => $item['webTitle'] ?? null,
                'description'  => $fields['trailText'] ?? null,
                'author'       => null, // often not provided directly
                'source'       => 'The Guardian',
                'category'     => $item['sectionName'] ?? 'general',
                'url'          => $item['webUrl'] ?? null,
                'image_url'    => $fields['thumbnail'] ?? null,
                'published_at' => $item['webPublicationDate'] ?? now(),
                'content'      => $fields['body'] ?? null,
            ];
        })->toArray();
    }
}
