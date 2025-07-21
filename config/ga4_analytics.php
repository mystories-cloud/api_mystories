<?php

return [
    'normal' => [
        'key_metrics' => [
            'metrics' => [
                'userEngagementDuration',
                'averageSessionDuration',
                'engagedSessions',
                'eventCount',
                'bounceRate',
                'engagementRate',
            ],
            'dimensions' => [
                'date',
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
        'page_analytics' => [
            'metrics' => [
                'screenPageViews',
                'newUsers',
            ],
            'dimensions' => [
                'fullPageUrl',
                'pagePath',
                'pageTitle',
                'date'
            ],
        ],
        'traffic_sources' => [
            'metrics' => [
                'sessions',
            ],
            'dimensions' => [
                'sessionSource',
                'date',
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
                'deviceCategory',
                'date'
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
    ],
    'metric_calculations' => [
        'form_abandon' => [
            "((", 'form_start', '-', 'form_submit', ')', '/', 'form_start', ')', '*', '100'
        ],
        'averageSessionDuration' => [
            'averageSessionDuration', '/', '60',
        ],
        'userEngagementDuration' => [
            'userEngagementDuration', '/', '60'
        ],
        'engagementRate' => [
            'engagedSessions', '/', 'session_start', '*', '100',
        ],
        'bounceRate' => [
            'bounceRate', '/', 'session_start', '*', '100'
        ],
        'conversion_rate' => [
            'signup', '/', 'first_visit', '*', '100',
        ]
    ]
];
