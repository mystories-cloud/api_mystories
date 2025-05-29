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

class SyncGA4Analytics implements ShouldQueue
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
        // $keyMetrics = $service->getData(new GA4DataRowTransformer, '2025-05-01', '2025-05-28', 'key_metrics');

        // foreach($keyMetrics['metrics'] as $index => $row)
        // {
        //     $metrics = mapArrayToMetricTable($row, $keyMetrics['dimensions'][$index]);
            
        //     KeyMetric::insert($metrics);
        // }

        // $trafficSources = $service->getData(new GA4DataRowTransformer, '2025-05-01', '2025-05-28', 'traffic_sources');

        // foreach($trafficSources['metrics'] as $index => $source) {
        //     $sources = mapArrayToTrafficSourceTable($source, $trafficSources['dimensions'][$index]);
        //     TrafficSource::insert($sources);
        // }

        // $countryAnalytics = $service->getData(new GA4DataRowTransformer, '2025-05-01', '2025-05-28', 'country_analytics');

        // // dd($countryAnalytics);

        // foreach($countryAnalytics['metrics'] as $index => $row)
        // {
        //     $analytics = mapArrayToCountryTable($row, $countryAnalytics['dimensions'][$index]);
        //     CountryAnalytics::insert($analytics);
        // } 

        $pageAnalytics = $service->getData(new GA4DataRowTransformer, '2025-05-01', '2025-05-28', 'page_analytics');

        // dd($countryAnalytics);

        foreach($pageAnalytics['metrics'] as $index => $row)
        {
            $dimensions = $pageAnalytics['dimensions'][$index];

            PageAnalytic::insert([
                'page_views' => $row['screenPageViews'],
                'new_users' => $row['newUsers'],
                'url' => $dimensions['fullPageUrl'],
                'path' => $dimensions['pagePath'],
                'title' => $dimensions['pageTitle'],
                'date' => Carbon::createFromFormat('YmdH', $dimensions['dateHour']),
            ]);
        } 
    }
}
 