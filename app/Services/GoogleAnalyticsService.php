<?php

namespace App\Services;

use App\Transformers\GA4DataRowTransformer;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\MinuteRange;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\RunRealtimeReportRequest;

class GoogleAnalyticsService
{
    protected $client;
    protected $propertyId;

    public function __construct()
    {
        $this->propertyId = config('services.google.analytics.ga4_property_id');

        $this->client = new BetaAnalyticsDataClient([
            'credentials' => storage_path(config('services.google.analytics.service_account_credientials')),
        ]);
    }

    public function getData(GA4DataRowTransformer $transformer, $startDate, $endDate, $configKey): array
    {
        $request = new RunReportRequest([
            'property' => 'properties/' . $this->propertyId,

            'date_ranges' => [
                new DateRange([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]),
            ],
            'order_bys' => [
                new OrderBy(['dimension' => new OrderBy\DimensionOrderBy(['dimension_name' => 'date'])]),
            ],

            'metrics' => mapKeyArray('normal.' . $configKey . '.metrics', Metric::class),

            'dimensions' => mapKeyArray('normal.' . $configKey . '.dimensions', Dimension::class),
        ]);

        $response = $this->client->runReport($request);

        return $transformer->transform($response);
        // return $response;
    }

    public function getRealtimeData(GA4DataRowTransformer $transformer, $config)
    {
        $request = new RunRealtimeReportRequest([
            'property' => 'properties/' . $this->propertyId,
            'metrics' => mapKeyArray('realtime.' . $config . '.metrics', Metric::class),
            'dimensions' => mapKeyArray('realtime.' . $config . '.dimensions', Dimension::class),
            'minute_ranges' => [
                new MinuteRange([
                    'start_minutes_ago' => 5,
                    'end_minutes_ago' => 0,
                ])
            ],
        ]);

        $response = $this->client->runRealtimeReport($request);

        return $transformer->transform($response);
    }
}
