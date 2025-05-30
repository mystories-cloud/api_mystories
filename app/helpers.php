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
    return array_key_exists('dateHour', $dimension) ? Carbon::createFromFormat('YmdH', $dimension['dateHour']) : 
    Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s');
}

function mapArray(string $key, $class): array
{
    return array_map(function ($key) use ($class) {
        return new $class(['name' => $key]);
    }, config('ga4_analytics.' . $key));
}