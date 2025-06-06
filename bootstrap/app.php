<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\Analytics\SyncRealtimeGA4Analytics;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    // ->withSchedule(function(Schedule $schedule) {
    //     $schedule->call(new SyncRealtimeGA4Analytics)->everyFiveMinutes();
    // })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
