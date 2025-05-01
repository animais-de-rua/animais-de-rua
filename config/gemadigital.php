<?php

return [
    'colors' => [
        ['#283240', '40, 50, 64'],
        ['#d73925', '215, 57, 37'],
    ],

    'sidebar' => [
        'filemanager' => false,
        'backups' => false,
        'translations' => true,
        'pages' => true,
        'authentication' => true,
        'settings' => true,
        'logs' => true,
    ],

    'build' => [
        'enabled' => false,
        'path' => public_path('data.json'),
        'classes' => [
            // \App\Models\Page::class => \App\Http\Resources\Page::class,
        ],
    ],
];
