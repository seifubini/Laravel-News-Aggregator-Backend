<?php

namespace App\Services\News;

use Illuminate\Support\Facades\Http;
use App\Services\Interfaces\NewsSourceInterface;
use Illuminate\Support\Facades\Log;

abstract class AbstractNewsService implements NewsSourceInterface
{
    /**
     * Perform a GET request to an external news API.
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    protected function get(string $url, array $params = []): array
    {
        try {
            //$response = Http::get($url, $params);
            $response = Http::withoutVerifying()->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning(static::class . ' failed: ' . $response->status());
        } catch (\Exception $e) {
            Log::error(static::class . ' error: ' . $e->getMessage());
        }

        return [];
    }

    abstract public function fetchArticles(): array;
}
