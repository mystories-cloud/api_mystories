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

class SyncGA4AnalyticsDaily implements ShouldQueue
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
    public function handle(GoogleAnalyticsService $service, $from = '', $to = ''): void
    {
        $from = !$from ? Carbon::now()->subDay()->format('Y-m-d') : $from;
        $to = !$to ? Carbon::now()->subDay()->format('Y-m-d') : $to;
        
        $keyMetrics = $service->getData(new GA4DataRowTransformer, $from, $to, 'key_metrics');

        foreach($keyMetrics['metrics'] as $index => $row)
        {
            KeyMetric::insertRows($row, $keyMetrics['dimensions'][$index]);
        }

        $trafficSources = $service->getData(new GA4DataRowTransformer, $from, $to, 'traffic_sources');

        foreach($trafficSources['metrics'] as $index => $source) 
        {
            TrafficSource::insertRow($row, $trafficSources['dimensions'][$index]);
        }

        $countryAnalytics = $service->getData(new GA4DataRowTransformer, $from, $to, 'country_analytics');
 
        foreach($countryAnalytics['metrics'] as $index => $row)
        {
            CountryAnalytics::insertRow($row, $countryAnalytics['dimensions'][$index]);
        } 

        $pageAnalytics = $service->getData(new GA4DataRowTransformer, $from, $to, 'page_analytics');

        foreach($pageAnalytics['metrics'] as $index => $row)
        {
            PageAnalytic::insertRow($row, $$pageAnalytics['dimensions'][$index]);
        } 
    }
}
 