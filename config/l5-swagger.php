<?php

return [
    'paths' => [
        'docs' => storage_path('api-docs'),
        'annotations' => base_path('app/Http/Controllers'),
    ],

    'info' => [
        'title' => 'Event Management API',
        'description' => 'API documentation for the Event Management System.',
        'version' => '1.0.0',
    ],

    'servers' => [
        [
            'url' => env('APP_URL', 'http://localhost'),
            'description' => 'Local server',
        ],
    ],
];