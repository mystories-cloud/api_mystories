<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BetaAppointmentController;
use App\Http\Controllers\BetaTesterController;
use App\Http\Controllers\UserController;
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
        $router->get('page-analytics', [AnalyticsController::class, 'getPageAnalytics']);     
        $router->post('collect', [AnalyticsController::class, 'collect']);
    });

    Route::prefix('beta-tester')->group(function(Router $router) {
        $router->get('/', [BetaTesterController::class, 'index']);
        $router->delete('/{betaTester}', [BetaTesterController::class, 'destroy']);
        $router->post('import', [BetaTesterController::class, 'import']);
    });

    Route::prefix('beta-appointment')->group(function(Router $router) {
        $router->get('/', [BetaAppointmentController::class, 'index']);
        $router->delete('/{betaAppointment}', [BetaAppointmentController::class, 'destroy']);
        $router->post('import', [BetaAppointmentController::class, 'import']);
    });

    Route::prefix('users')->group(function(Router $router) {
        $router->get('get-started', [UserController::class, 'getStarted']);
    });
});
