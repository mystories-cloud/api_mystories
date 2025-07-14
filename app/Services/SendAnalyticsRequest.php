<?php

namespace App\Services;

use App\Models\AnalyticsRequestLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SendAnalyticsRequest
{
    public function handle(Request $request)
    {
        $request->validate([
            'url' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (stripos($value, 'google') === false) {
                        $fail("The $attribute must contain the word 'google'.");
                    }
                },
            ],
            'body' => 'nullable|string',
        ]);

        $analyticsLog = AnalyticsRequestLog::create([
            'url' => $request->get('url'),
            'method' => $request->get('method'),
            'body' => $request->get('body'),
        ]);

        $url =  $request->get('url');

        $body = $request->get('body');

        try {
            $response = Http::withHeaders([
                'User-Agent' => $request->header('User-Agent'),
                'Content-Type' => $request->header('Content-Type'),
            ])
            ->withBody($body, 'text/plain;charset=UTF-8')
            ->post($url);

            $analyticsLog->status = $response->status();

        } catch (Exception $e) {
            $analyticsLog->status = $response->status();
            $analyticsLog->exception = serialize($e);
        }

        $analyticsLog->save();
    }
}
