<?php

use Carbon\Carbon;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;

function mapKeyArray(string $key, $class): array
{
    $array = mapArray($key, $class);

    if ($key == 'dimension') {
        $array = array_push($array, mapArray('default_dims', Dimension::class));
    }

    return $array;
}

function convertGA4DateHourToDate($dimension)
{
    return array_key_exists('date', $dimension) ? Carbon::createFromFormat('YmdH', $dimension['date']) :
        Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s');
}

function mapArray(string $key, $class): array
{
    return array_map(function ($key) use ($class) {
        return new $class(['name' => $key]);
    }, config('ga4_analytics.' . $key));
}

function queryDateFilter($query)
{

    if (request()->get('from')) {
        $query->where('date', '>=', request()->get('from'));
    }

    if (request()->get('to')) {
        $query->where('date', '<=', request()->get('to'));
    }

    return $query;
}

function getCountDiff($model)
{
    $lastMonth = $model::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->count();
    $currentMonth = $model::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->count();
    return ($lastMonth == 0 ? ($currentMonth > 0 ? 100 : 0) : number_format((($currentMonth - $lastMonth) / $lastMonth) * 100, 2)) . '%';
}
