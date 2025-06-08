<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrafficSource extends Model
{
    public $fillable = [
        'source',
        'value',
        'date',
    ];

    protected $connection = 'analytics_connection';

    public static function insertRow($row, $dimension)
    {
        foreach ($row as $key => $value) {
            static::create([
                'source' => $dimension['sessionSource'],
                'value' => $value,
                'date' => convertGA4DateHourToDate($dimension),
            ]);
        }
    }

    public static function getSources()
    {
        return queryDateFilter(static::select(DB::raw('SUM(value) as count'), DB::raw('REPLACE(REPLACE(source, "(", ""), ")", "") as source'))
            ->groupBy('source'))
            ->pluck('count', 'source')
            ->toArray();
    }
}
