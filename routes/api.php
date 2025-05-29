<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('analytics')->group(function(Router $router) {
        $router->get('key-analytics', [AnalyticsController::class, 'getKeyAnalytics']);     
        $router->get('traffic-sources', [AnalyticsController::class, 'getTrafficSources']);     
        $router->get('country-analytics', [AnalyticsController::class, 'getCountryAnalytics']);     
        $router->get('monthly-analytics', [AnalyticsController::class, 'monthlyAnalytics']);     
    });
});
