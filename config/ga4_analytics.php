<?php

return [
    'normal' => [
        'key_metrics' => [
            'metrics' => [
                'userEngagementDuration',
                'averageSessionDuration',
                'engagedSessions',
                'eventCount',
            ],
            'dimensions' => [
                'dateHour',
                'eventName',
            ],
            'events' => [
                'signup',
                'form_submit',
                'form_start',
                'scroll',
                'session_start',
                'page_view',
                'first_visit',
                'user_engagement',
            ],
        ],
        'pages_analytics' => [
            'metrics' => [
                'screenPageViews',
                'newUsers',
            ],
            'dimensions' => [
                'fullPageUrl',
                'pagePath',
                'pageTitle',
                'pageReferrer',
            ],
        ],
        'traffic_sources' => [
            'metrics' => [
                'sessions',
            ],
            'dimensions' => [
                'sessionSource',
                'dateHour',
            ],
        ],
        'country_analytics' => [
            'metrics' => [
                'sessions',
                'screenPageViews',
                'newUsers',
            ],
            'dimensions' => [
                'country',
                'dateHour',
            ],
        ],
        'device_analytics' => [
            'metrics' => [
                'screenPageViews',
            ],
            'dimesions' => [
                'deviceCategory'
            ],
        ],
        'default_dims' => [
            'date',
            'hour'
        ],
    ],
    'realtime' => [
        'key_metrics' => [
            'metrics' => [
                'eventCount',
            ],
            'dimensions' => [
                'eventName',
            ],
            'events' => [
                'signup',
                'form_submit',
                'form_start',
                'scroll',
                'sessions_start',
                'page_view',
                'first_visit',
                'user_engagement',
            ],
        ],
        'country_analytics' => [
            'metrics' => [
                'screenPageViews',
            ],
            'dimensions' => [
                'country',
            ]
        ],
        'device_analytics' => [
            'metrics' => [
                'screenPageViews'
            ],
            'dimensions' => [
                'deviceCategory'
            ]
        ],
        'signup_events_analytics' => [
            'metrics' => [
                'eventCount'
            ],
            'events' => [
                'signup_start',
                'signup_verification',
                'signup_package_selection',
                'signup_payment',
                'signup',
            ],
            'dimensions' => [
                'eventName',
            ],
        ],
    ],
    'metric_calculations' => [
        'form_abandon' => [
            "((", 'form_start', '-', 'form_submit', ')', '/', 'form_start', ')', '*', '100'
        ],
        'engagement_rate' => [
            'engagedSessions', '/', 'session_start', '*', '100',
        ],
        'bounce_rate' => [
            '(', 'session_start', '-', 'engagedSessions', ')', '/', 'session_start', '*', '100'
        ],
        'averageSessionDuration' => [
            'averageSessionDuration', '/', '60',
        ],
        'userEngagementDuration' => [
            'userEngagementDuration', '/', '60',
        ],
    ]
];
