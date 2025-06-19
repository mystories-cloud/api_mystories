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
    // User::create([
    //     'first_name' => 'Site',
    //     'last_name' => 'User',
    //     'email' => 'mystories@gmail.com',
    //     'password' => '1234',
    //     'type' => 'site',
    // ]);

    // dd(User::first()->createToken('site_token')->plainTextToken);
    // // SyncGA4Analytics::dispatch();

    //  $data = KeyMetric::select(DB::raw('SUM(value) AS value'), 'key')
    //  ->where('date', '>=', '2025-01-01')
    //         ->where('date', '<=', '2025-01-01')
    //         ->groupBy('key')

    //         ->pluck('value', 'key')
    //         ->toArray();

    //     foreach (KeyMetric::$metricKeys as $key) {
    //         $expression = config('ga4_analytics.metric_calculations.' . $key);

    //         $expression = $expression ? implode(' ', array_map(function ($item) use ($data) {
    //             return array_key_exists($item, $data) ? $data[$item] : (in_array($item, KeyMetric::$metricKeys) ? 0 : $item);
    //         }, $expression)) : "";

    //         dd($expression);

    //         $data[$key] = round($expression ? eval("return $expression;") : ($data[$key] ?? 0), 2);
    //     }

    //     return $data;
    // SyncRealtimeGA4Analytics::dispatch();

    $from = '2025-06-17';
    $to = '2025-06-18';

    $keyMetrics = $service->getData(new GA4DataRowTransformer, $from, $to, 'key_metrics');

    foreach ($keyMetrics['metrics'] as $index => $row) {
        KeyMetric::insertRows($row, $keyMetrics['dimensions'][$index]);
    }

    $trafficSources = $service->getData(new GA4DataRowTransformer, $from, $to, 'traffic_sources');

    foreach ($trafficSources['metrics'] as $index => $source) {
        TrafficSource::insertRow($row, $trafficSources['dimensions'][$index]);
    }

    $countryAnalytics = $service->getData(new GA4DataRowTransformer, $from, $to, 'country_analytics');

    foreach ($countryAnalytics['metrics'] as $index => $row) {
        CountryAnalytics::insertRow($row, $countryAnalytics['dimensions'][$index]);
    }

    $pageAnalytics = $service->getData(new GA4DataRowTransformer, $from, $to, 'page_analytics');

    foreach ($pageAnalytics['metrics'] as $index => $row) {
        PageAnalytic::insertRow($row, $pageAnalytics['dimensions'][$index]);
    }
});

Route::get('/analytics', [AnalyticsController::class, 'getKeyAnalytics']);
