<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageAnalytic extends Model
{
    public $fillable = [
        'url',
        'title',
        'path',
        'page_views',
        'new_users',
        'date',
    ];

    public static function insertRow($row, $dimension)
    {
        static::create([
            'page_views' => $row['screenPageViews'],
            'new_users' => $row['newUsers'],
            'url' => $dimension['fullPageUrl'],
            'path' => $dimension['pagePath'],
            'title' => $dimension['pageTitle'],
            'date' => convertGA4DateHourToDate($dimension),
        ]);
    }

    public static function getData()
    {
        return static::select(DB::raw('SUM(page_views) as page_views'), DB::raw('SUM(new_users) as new_users'), 'path', 'title')
            ->groupBy('path', 'title')
            ->where('path', 'not like', '%payment%')
            ->orderByDesc('page_views')
            ->get();
    }
}
