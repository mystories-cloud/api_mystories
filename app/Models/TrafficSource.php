<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrafficSource extends Model
{
    public $fillable = [
        'source',
        'value',
        'dateHour',
    ];

    public static function getSources()
    {
        return static::select(DB::raw('SUM(value) as count'), DB::raw('REPLACE(REPLACE(source, "(", ""), ")", "") as source'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();
    }
}
