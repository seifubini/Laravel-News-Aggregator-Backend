<?php

namespace App\Services;

use App\Models\Article;
use App\Services\Interfaces\NewsSourceInterface;

class SyncNewsService
{
    /**
     * @var NewsSourceInterface[]
     */
    protected array $sources;

    public function __construct(array $sources)
    {
        $this->sources = $sources;
    }

    public function sync(): void
    {
        foreach ($this->sources as $source) {
            foreach ($source->fetchArticles() as $article) {
                Article::storeOrUpdate($article);
            }
        }
    }
}
