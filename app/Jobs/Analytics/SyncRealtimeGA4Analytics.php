<?php

namespace App\Jobs\Analytics;

use App\Models\CountryAnalytics;
use App\Models\KeyMetric;
use App\Models\PageAnalytic;
use App\Models\TrafficSource;
use App\Services\GoogleAnalyticsService;
use App\Transformers\GA4DataRowTransformer;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncRealtimeGA4Analytics implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleAnalyticsService $service): void
    {
        $keyMetrics = $service->getRealtimeData(new GA4DataRowTransformer, 'key_metrics');

        foreach($keyMetrics['metrics'] as $index => $row)
        {
            KeyMetric::insertRows($row, $keyMetrics['dimensions'][$index]);
        }

        $countryAnalytics = $service->getRealtimeData(new GA4DataRowTransformer, 'country_analytics');
 
        foreach($countryAnalytics['metrics'] as $index => $row)
        {
            CountryAnalytics::insertRow($row, $countryAnalytics['dimensions'][$index]);
        } 
    }
}
 