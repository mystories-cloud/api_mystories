<?php

namespace App\Models;

use Carbon\Carbon;
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
        'button_click',
        'link_click',
        'click',
        'user_engagement',
        'bounce_rate',
        'engagement_rate',
        'form_abandon',
        'conversion_rate',
    ];

    public static function insertRows($row, $dimension)
    {
        foreach ($row as $key => $value) {
            static::create([
                'key' => $key,
                'value' => $value,
                'date' => convertGA4DateHourToDate($dimension),
            ]);
        }
    }

    public static function getKeyAnalytics()
    {
        $data = static::select(DB::raw('SUM(value) AS value'), 'key')
            ->groupBy('key')
            ->pluck('value', 'key')
            ->toArray();

        foreach (self::$metricKeys as $key) {
            $expression = config('ga4_analytics.metric_calculations.' . $key);

            $expression = $expression ? implode(' ', array_map(function ($item) use ($data) {
                return array_key_exists($item, $data) ? $data[$item] : $item;
            }, $expression)) : "";

            $data[$key] = round($expression ? eval("return $expression;") : ($data[$key] ?? 0), 2);
        }

        return $data;
    }
}
