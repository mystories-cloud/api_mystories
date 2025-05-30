<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CountryAnalytics extends Model
{
    public $fillable = [
        'country',
        'page_views',
        'sessions',
        'newUsers',
        'date',
    ];

    public static function insertRow($row, $dimension)
    {
        static::create([
            'country' => $dimension['country'],
            'page_views' => $row['screenPageViews'],
            'sessions' => array_key_exists('session', $row) ? $row['sessions'] : 0,
            'newUsers' => array_key_exists('newUsers', $row) ? $row['newUsers'] : 0,
            'date' => convertGA4DateHourToDate($dimension),
        ]);
    }

    public static function getData()
    {
        return static::select(DB::raw('SUM(page_views) as page_views'), DB::raw('SUM(sessions) as sessions'), DB::raw('SUM(newUsers) as newUsers'), 'country')
            ->groupBy('country')
            ->get();
    }
}
