<?php

namespace App\Http\Controllers;

use App\Models\CountryAnalytics;
use App\Models\KeyMetric;
use App\Models\PageAnalytic;
use App\Models\TrafficSource;
use App\Traits\ApiResponse\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    use Response;

    public function getKeyAnalytics()
    {
        return $this->response(KeyMetric::getKeyAnalytics());
    }

    public function getTrafficSources()
    {
        // dd(TrafficSource::getSources());
        return $this->response(TrafficSource::getSources());
    }

    public function getCountryAnalytics()
    {
        // return CountryAnalytics::getData();
        // dd(TrafficSource::getSources());
        return $this->response(CountryAnalytics::getData());
    }

    public function monthlyAnalytics()
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        return $this->response(DB::table('key_metrics')
            ->selectRaw('DATE(`date`) as day, `key`, SUM(`value`) as total')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereIn('key', ['page_views', 'signup'])
            ->groupBy(DB::raw('DATE(`date`)'), 'key')
            ->orderBy('day')
            ->get()
            ->groupBy('day'));
    }

    public function getPageAnalytics()
    {
        return $this->response(PageAnalytic::getData());
    }
}
