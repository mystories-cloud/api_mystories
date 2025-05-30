<?php

use App\Jobs\Analytics\SyncGA4AnalyticsDaily;
use App\Jobs\Analytics\SyncRealtimeGA4Analytics;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SyncRealtimeGA4Analytics)->everyFiveMinutes();

Schedule::job(new SyncGA4AnalyticsDaily)->daily();

