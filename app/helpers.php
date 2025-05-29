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

function mapArray(string $key, $class): array
{
    return array_map(function ($key) use ($class) {
        return new $class(['name' => $key]);
    }, config('ga4_analytics.' . $key));
}


function mapArrayToMetricTable($row, $dimension)
{
    $result = [];

    foreach ($row as $key => $value) {
        $result[] = [
            'key' => $key,
            'value' => $value,
            'date' => Carbon::createFromFormat('YmdH', $dimension['dateHour']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    return $result;
}

function mapArrayToTrafficSourceTable($row, $dimension)
{
    $result = [];
    foreach ($row as $key => $value) {
        $result[] = [
            'source' => $dimension['sessionSource'],
            'value' => $value,
            'date' => Carbon::createFromFormat('YmdH', $dimension['dateHour']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
    return $result;
}

function mapArrayToCountryTable($row, $dimension)
{
    return [
        'country' => $dimension['country'],
        'page_views' => $row['screenPageViews'],
        'sessions' => $row['sessions'],
        'newUsers' => $row['newUsers'],
        'date' => Carbon::createFromFormat('YmdH', $dimension['dateHour']),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
}
