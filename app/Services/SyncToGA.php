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

        $measurementId = 'G-2C8H727VSV';
        $apiSecret = 'VuZprBzSS8ue7PZXPzAYYA'; // Keep this secure and don't expose it client-side!

        foreach ($pageViews as $index => $pageView) {
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
            $clientId = "1054051101.167886611".$index;

            // The 'session_id' should also ideally come from the original frontend session.
            // It's typically a Unix timestamp (in seconds).
            // If $pageView->session_id is a valid timestamp, use it directly.
            // If not, you could generate one based on the page view's creation time:
            $sessionId = $pageView->session_id ?: Carbon::parse($pageView->created_at)->getTimestamp();

            // Convert 'created_at' to microseconds for 'timestamp_micros'.
            $timestampMicros =  (int)(microtime(true) * 1_000_000);

            // 3. Construct the Measurement Protocol payload
            $payload = [
                'client_id' => $clientId,
                'timestamp_micros' => $timestampMicros,
                'ip_override' => $pageView->ip,
                'events' => [
                    [
                        'name' => 'page_view',
                        'params' => [
                            'page_location' => 'https://mystories.cloud',
                            'page_referrer' => '',
                            'session_id' => (int) $sessionId,
                            'engagement_time_msec' => 1500,
                        ],
                    ],
                ],
            ];

            // 4. Send the request to GA4's Debug endpoint
            // The '/debug/mp/collect' endpoint validates your payload and provides errors,
            // but does NOT send data to your live GA4 reports, making it ideal for testing.
            dump($payload);
            $response = Http::post("https://www.google-analytics.com/mp/collect?measurement_id={$measurementId}&api_secret={$apiSecret}", $payload);

            // 5. Handle the response
            if ($response->successful()) {
                dump("GA4 debug events sent successfully for URL: {$pageView->url}. Response: " . $response->body() . "\n");
            } else {
                dump("Error sending GA4 debug events for URL: {$pageView->url}. Status: " . $response->status() . " - Body: " . $response->body() . "\n");
            }
        }
    }
}