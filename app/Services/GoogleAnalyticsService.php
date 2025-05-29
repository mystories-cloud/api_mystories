<?php

namespace App\Services;

use App\Transformers\GA4DataRowTransformer;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\NumericFilter;
use Google\Analytics\Data\V1beta\Filter\NumericFilter\Operation;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\NumericValue;
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
            // 'dimension_filter' => new FilterExpression([
            //     'filter' => new Filter([
            //         'field_name' => 'hour', // Filter on the 'hour' dimension
            //         'numeric_filter' => new NumericFilter([
            //             'operation' => Operation::EQUAL,
            //             'value' => new NumericValue(['int64_value' => $hour]),
            //         ]),
            //     ]),
            // ]),
            'order_bys' => [
                new OrderBy([ 'dimension' => new OrderBy\DimensionOrderBy(['dimension_name' => 'dateHour']) ]),
            ],

            'metrics' => mapKeyArray('normal.'.$configKey.'.metrics', Metric::class),

            'dimensions' => mapKeyArray('normal.'.$configKey.'.dimensions', Dimension::class),
        ]);

        $response = $this->client->runReport($request);

        return $transformer->transform($response);
        // return $response;
    }

    public function getRealtimeData(GA4DataRowTransformer $transformer)
    {
        $request = new RunRealtimeReportRequest([
            'property' => 'properties/' . $this->propertyId,
            'metrics' => mapKeyArray('realtime.key_metrics.metrics', Metric::class),
            'dimensions' => mapKeyArray('realtime.key_metrics.dimensions', Dimension::class),
        ]);

        $response = $this->client->runRealtimeReport($request);

        dd($transformer->transform($response));

        dd($response);
    }
}
