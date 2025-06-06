<?php

namespace App\Services;

use App\Models\PageView;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon; // Ensure Carbon is imported

class SyncToGA
{
    /**
     * Syncs PageView data to Google Analytics 4 via the Measurement Protocol.
     * Events are configured to appear in GA4's DebugView for easy testing.
     */
    public function sync(): void
    {
        // 1. Fetch relevant PageView records
        // Only fetch records within the last 72 hours, as GA4 has a processing limit for historical data.
        $pageViews = PageView::where('created_at', '>', Carbon::now()->subHours(72))
            ->where('url', 'like', '%utm%')
            ->where('country', '<>', 'Pakistan')
            ->get();

        $measurementId = 'G-N8H7STCG3E';
        $apiSecret = 'gQgGDNA2QFCUXcW0Uz8Kdg'; // Keep this secure and don't expose it client-side!

        foreach ($pageViews as $pageView) {
            // 2. Determine client_id and session_id
            // For DebugView to work reliably, the 'client_id' should ideally match one
            // from a browser currently in GA4's debug mode (e.g., via GTM Preview or GA Debugger Extension).
            //
            // Best practice: If your PageView model stores the actual client_id from the frontend, use that:
            // $clientId = $pageView->client_id;
            //
            // For testing (if you can't get a real client_id from DB):
            // You can manually get a client_id from your browser's '_ga' cookie (e.g., "123456789.1678888888")
            // while your browser is in debug mode, and use it here for testing.
            // Otherwise, a newly generated one might not show up in DebugView without prior client-side context.
            $clientId = 123456789.1678888888;

            // The 'session_id' should also ideally come from the original frontend session.
            // It's typically a Unix timestamp (in seconds).
            // If $pageView->session_id is a valid timestamp, use it directly.
            // If not, you could generate one based on the page view's creation time:
            $sessionId = $pageView->session_id ?: Carbon::parse($pageView->created_at)->getTimestamp();

            // Convert 'created_at' to microseconds for 'timestamp_micros'.
            $timestampMicros = Carbon::parse($pageView->created_at)->getTimestampMs() * 1_000;

            // 3. Construct the Measurement Protocol payload
            $payload = [
                'client_id' => $clientId,
                'timestamp_micros' => $timestampMicros,
                'ip_override' => $pageView->ip, // Useful for attributing traffic in reports, though not always visible in DebugView
                'user_properties' => [
                    // User properties are for persistent user attributes, not session-specific data.
                    // Only include actual user properties here.
                    'device_type' => ['value' => $pageView->device_type],
                    'http_method' => ['value' => 'GET'], // Use $pageView->method if available in your model
                ],
                'events' => [
                    [
                        'name' => 'page_view', // Using the standard GA4 event name for page views
                        'params' => [
                            'page_location' => $pageView->url,
                            'page_referrer' => '', // Populate this if you have referrer data in your PageView model
                            'session_id' => (int) $sessionId, // 'session_id' is an event parameter, cast to int
                            'engagement_time_msec' => 1000, // Example engagement time (in milliseconds)
                            'debug_mode' => 1, // *** THIS IS CRUCIAL for events to appear in DebugView! ***
                            // You can add other custom parameters here, e.g., 'utm_source', 'utm_medium', etc.
                            // based on parsing $pageView->url
                        ],
                    ],
                    // IMPORTANT: 'first_visit' and 'session_start' events are generally auto-collected by
                    // GA4 client-side tags. Sending them directly via Measurement Protocol is not the primary
                    // use case and can lead to unexpected behavior or incomplete reporting.
                    // Focus on sending 'page_view' and other custom events for server-side data.
                ],
            ];

            // 4. Send the request to GA4's Debug endpoint
            // The '/debug/mp/collect' endpoint validates your payload and provides errors,
            // but does NOT send data to your live GA4 reports, making it ideal for testing.
            $response = Http::post("https://www.google-analytics.com/debug/mp/collect?measurement_id={$measurementId}&api_secret={$apiSecret}", $payload);

            // 5. Handle the response
            if ($response->successful()) {
                echo "GA4 debug events sent successfully for URL: {$pageView->url}. Response: " . $response->body() . "\n";
            } else {
                echo "Error sending GA4 debug events for URL: {$pageView->url}. Status: " . $response->status() . " - Body: " . $response->body() . "\n";
            }
        }
    }
}