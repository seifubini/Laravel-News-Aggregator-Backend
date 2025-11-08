<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SyncNewsService;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store latest articles from news APIs';

    /**
     * Execute the console command.
     */
    public function handle(SyncNewsService $service)
    {
        $service->sync();
        $this->info('News articles synced successfully.');
    }
}
