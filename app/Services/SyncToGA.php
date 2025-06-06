<?php

namespace App\Services;

use App\Models\PageView;
use Illuminate\Support\Facades\Http;

class SyncToGA
{

    public function sync()
    {
        $pageViews = PageView::where('created_at', '>', '2025-06-05')
            ->where('url', 'like', '%utm%')
            ->where('country', '<>', 'Pakistan')
            ->get();

        foreach ($pageViews as $pageView) {

            $measurementId = 'G-N8H7STCG3E';
            $apiSecret = 'gQgGDNA2QFCUXcW0Uz8Kdg';

            // Example data from your DB
            $url = $pageView->url;          // url
            $ip = $pageView->ip;                        // ip
            $method = 'GET';                             // method
            $userAgent = $pageView->user_agent;  // user_agent
            $country = $pageView->country;                       // country
            $city = $pageView->city;                           // city
            $deviceType = $pageView->device_type;                     // device_type

            // Generate or fetch client_id and session_id
            $clientId = sprintf('%d.%d', mt_rand(100000000, 999999999), time());;
            $sessionId = $pageView->session_id;

            // Current timestamp in microseconds
            $timestampMicros = \Carbon\Carbon::parse($pageView->created_at)->getTimestamp() * 1_000_000;

            $payload = [
                'client_id' => $clientId,
                'timestamp_micros' => $timestampMicros,
                'user_location' => [
                    'country' => $country,
                    'city' => $city,
                ],
                'ip_override' => $ip,
                'user_properties' => [
                    'device_type' => ['value' => $deviceType],
                    'http_method' => ['value' => $method],
                    'session_id' => ['value' => $sessionId],
                ],
                'events' => [
                    [
                        'name' => 'first_visit',
                        'params' => [
                            'page_location' => $url,
                        ],
                    ],
                    [
                        'name' => 'session_start',
                        'params' => [
                            'session_id' => $sessionId,
                        ],
                    ],
                    [
                        'name' => 'page_view',
                        'params' => [
                            'page_location' => $url,
                            'page_referrer' => '',  // Add if you have referrer
                        ],
                    ],
                    [
                        'name' => 'user_engagement',
                        'params' => [
                            'engagement_time_msec' => 10,  // example 15 sec engagement
                            'session_id' => $sessionId,
                        ],
                    ],
                ],
            ];

            $response = Http::post("https://www.google-analytics.com/mp/collect?measurement_id={$measurementId}&api_secret={$apiSecret}", $payload);

            if ($response->successful()) {
                echo "GA4 events sent successfully.";
            } else {
                echo "Error sending GA4 events: " . $response->body();
            }
        }
    }
}
