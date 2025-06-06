<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    public $fillable = [
        'url',
        'method',
        'ip',
        'user_agent',
        'session_id',
        'country',
        'city',
        'device_type',
        'longitude',
        'latitude',
    ];

    public static function getCountDiff()
    {
        return getCountDiff(self::class);
    }

    public static function getMonthlyVisitors()
    {

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $pageviews = PageView::selectRaw('DATE(created_at) as date, Count(*) as views')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('views', 'date');

        $dailyViews = [];
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->addDays(1);
            $dateVisitor = $pageviews->get($date->toDateString());
            $dailyViews[] = $dateVisitor ? $dateVisitor : 0;
        }
        return $dailyViews;
    }

    public static function getAverageDailyVisitors()
    {
        // Get the total number of page views
        $totalPageViews = PageView::count();

        // Return 0 if no page views exist
        if ($totalPageViews === 0) {
            return 0;
        }

        // Get the creation date of the first page view (oldest record)
        $firstPageViewDate = PageView::min('created_at');

        // Calculate the number of days between the first page view and now
        $days = Carbon::parse($firstPageViewDate)->diffInDays(Carbon::now()) ?: 1; // Avoid division by zero

        // Calculate the average daily visitors
        return intval($totalPageViews / $days);
    }

    private function get()
    {
        return PageView::first();
    }

    public static function getBounceRate()
    {

        $sessionsCount = PageView::groupBy('session_id')->selectRaw('count(session_id) AS count')->get();
        $totalSessions = PageView::count();
        $singlePageSessions = 0;
        foreach ($sessionsCount as $session) {
            if ($session->count == 1) {
                $singlePageSessions++;
            }
        }
        return number_format($totalSessions > 0 ? ($singlePageSessions / $totalSessions) * 100 : 0, 2);
    }

    public static function getViewsByCountry()
    {
        return PageView::whereMonth('created_at', now()->month)
            ->groupBy('country')->selectRaw("IFNULL(country,'Unkown') AS country, count(*) as count")
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }

    public static function getMonthlyViews()
    {
        return PageView::whereMonth('created_at', now()->month)->count();
    }

    public static function getMonthlyViewsByDeviceType()
    {
        return PageView::whereMonth('created_at', now()->month)
            ->groupBy('device_type')
            ->selectRaw("IFNULL(device_type, 'Unknown') as device_type, count(*) as count")
            ->orderBy('device_type')
            ->get()
            ->pluck('count', 'device_type');
    }

    public static function getViewsByURL()
    {
        return PageView::whereMonth('created_at', now()->month)
            ->selectRaw("REPLACE(url, 'http://" . app('currentTenant')->domain . "', '') AS url, COUNT(DISTINCT session_id) as unique_sessions, count(*) as count")
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }
}
