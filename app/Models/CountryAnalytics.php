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

    public static function getData()
    {
         return static::select(DB::raw('SUM(page_views) as page_views'), DB::raw('SUM(sessions) as sessions'), DB::raw('SUM(newUsers) as newUsers'), 'country')
            ->groupBy('country')
            ->get();
    }
}
