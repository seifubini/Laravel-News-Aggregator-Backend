<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('news:fetch')
    ->hourly()
    ->withoutOverlapping()
    ->onSuccess(fn() => Log::info('Scheduled news sync ran successfully at ' . now()))
    ->onFailure(fn() => Log::error('Scheduled news sync failed at ' . now()));
