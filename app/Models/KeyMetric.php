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

    protected $connection = 'analytics_connection';

    public static array $metricKeys = [
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
        'bounceRate',
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
        $data = queryDateFilter(static::select(DB::raw('SUM(value) AS value'), 'key')
            ->groupBy('key'))
            ->pluck('value', 'key')
            ->toArray();

        foreach (self::$metricKeys as $key) {
            $expression = config('ga4_analytics.metric_calculations.' . $key);

            $expression = $expression
                ? implode(' ', array_map(function ($item) use ($data) {
                    return array_key_exists($item, $data) ? $data[$item] : $item;
                }, $expression))
                : "";

            $result = 0;

            if ($expression) {
                // Use output buffering and error suppression to safely handle eval
                ob_start();
                try {
                    $evaluated = @eval("return $expression;");
                    if (is_numeric($evaluated)) {
                        $result = round($evaluated, 2);
                    }
                } catch (\Throwable $e) {
                    // silently catch any errors
                    $result = 0;
                }
                ob_end_clean();
            } else {
                $result = round($data[$key] ?? 0, 2);
            }

            $data[$key] = round($result ? $result: ($data[$key] ?? 0), 2);
        }

        return $data;
    }
}
