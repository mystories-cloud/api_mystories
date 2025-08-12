<?php

use App\Http\Controllers\AnalyticsController;
use App\Jobs\Analytics\SyncGA4Analytics;
use App\Jobs\Analytics\SyncRealtimeGA4Analytics;
use App\Models\CountryAnalytics;
use App\Models\KeyMetric;
use App\Models\PageAnalytic;
use App\Models\TrafficSource;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Services\GoogleAnalyticsService;
use App\Services\SyncToGA;
use App\Transformers\GA4DataRowTransformer;
use Illuminate\Support\Facades\DB;

Route::get('/', function (GoogleAnalyticsService $service) {
    // return view('welcome');
    // dd($service->getData(new GA4DataRowTransformer,'2025-05-01', '2025-05-28', 'traffic_sources'));
    // dd(DB::connection()->getDatabaseName());
    // dd(User::where('email', 'mystories@gmail.com')->first()->createToken('site_token')->plainTextToken);
    // // SyncGA4Analytics::dispatch();

    // $from = '2025-03-01';
    // $to = '2025-07-30';

    // $keyMetrics = $service->getData(new GA4DataRowTransformer, $from, $to, 'key_metrics');

    //     foreach($keyMetrics['metrics'] as $index => $row)
    //     {
    //         KeyMetric::insertRows($row, $keyMetrics['dimensions'][$index]);
    //     }

    //     $keyMetrics = $service->getData(new GA4DataRowTransformer, $from, $to, 'key_metrics_events');

    //     foreach($keyMetrics['metrics'] as $index => $row)
    //     {
    //         KeyMetric::insertRows($row, $keyMetrics['dimensions'][$index]);
    //     }

    //     $trafficSources = $service->getData(new GA4DataRowTransformer, $from, $to, 'traffic_sources');

    //     foreach($trafficSources['metrics'] as $index => $source) 
    //     {
    //         TrafficSource::insertRow($row, $trafficSources['dimensions'][$index]);
    //     }

    //     $countryAnalytics = $service->getData(new GA4DataRowTransformer, $from, $to, 'country_analytics');
 
    //     foreach($countryAnalytics['metrics'] as $index => $row)
    //     {
    //         CountryAnalytics::insertRow($row, $countryAnalytics['dimensions'][$index]);
    //     } 

    //     $pageAnalytics = $service->getData(new GA4DataRowTransformer, $from, $to, 'page_analytics');

    //     foreach($pageAnalytics['metrics'] as $index => $row)
    //     {
    //         PageAnalytic::insertRow($row, $pageAnalytics['dimensions'][$index]);
    //     } 
});

Route::get('/analytics', [AnalyticsController::class, 'getKeyAnalytics']);
