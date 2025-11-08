<?php

namespace App\Providers;

use App\Services\News\GuardianService;
use App\Services\News\NYTService;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\NewsSourceInterface;
use App\Services\SyncNewsService;
use App\Services\News\NewsApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind each service to the interface (optional but clean)
        $this->app->bind(NewsSourceInterface::class, function () {
            return [
                new NewsApiService(),
                new GuardianService(),
                new NYTService(),
            ];
        });

        // Bind SyncNewsService as singleton
        $this->app->singleton(SyncNewsService::class, function ($app) {
            $sources = $app->make(NewsSourceInterface::class);
            return new SyncNewsService($sources);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
