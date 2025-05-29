<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KeyMetric extends Model
{
    public $fillable = [
        'key',
        'value',
        'date'
    ];

    protected static array $metricKeys = [
        'userEngagementDuration',
        'averageSessionDuration',
        'engagedSessions',
        'signup',
        'form_submit',
        'form_start',
        'scroll',
        'session_start',
        'page_view',
        'first_visit',
        'user_engagement',
        'bounce_rate',
        'engagement_rate',
        'form_abandon',
    ];

    public static function getKeyAnalytics()
    {
        $data = static::select(DB::raw('SUM(value) AS value'), 'key')
            ->groupBy('key')
            ->pluck('value', 'key')
            ->toArray();

        foreach(self::$metricKeys as $key)
        {
            $expression = config('ga4_analytics.metric_calculations.'.$key);
            
            $expression = $expression ? implode(' ', array_map(function ($item) use ($data) {
                return array_key_exists($item, $data) ? $data[$item] : $item;
            }, $expression)) : "";

            $data[$key] = $expression ? eval("return $expression;") : ($data[$key] ?? 0);
        }

        return $data;
    }
    
}
