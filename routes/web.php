<?php

use App\Http\Controllers\AnalyticsController;
use App\Jobs\Analytics\SyncGA4Analytics;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Services\GoogleAnalyticsService;
use App\Transformers\GA4DataRowTransformer;

Route::get('/', function (GoogleAnalyticsService $service) {
    // return view('welcome');
    // dd($service->getData(new GA4DataRowTransformer,'2025-05-01', '2025-05-28', 'traffic_sources'));
    // User::create([
    //     'name' => 'Site User',
    //     'email' => 'mystories@gmail.com',
    //     'password' => '1234',
    //     'type' => 'site',
    // ]);

    // dd(User::first()->createToken('site_token')->plainTextToken);
    SyncGA4Analytics::dispatch();
});

Route::get('/analytics', [AnalyticsController::class, 'getKeyAnalytics']);
