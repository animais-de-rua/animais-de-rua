<?php

return [
    'colors' => [
        ['#283240', '40, 50, 64'],
        ['#4171b7', '65, 113, 183'], // blue
        // ['#b26a00', '178, 106, 0'], // orange
        // ['#702f39', '112, 47, 57'], // red
    ],

    'sidebar' => [
        'filemanager' => false,
        'backups' => false,
        'translations' => true,
        'pages' => false,
        'authentication' => true,
        'settings' => false,
        'logs' => true,
    ],

    'build' => [
        'enabled' => true,
        'path' => public_path('data.json'),
        'classes' => [
            // \App\Models\Page::class => \App\Http\Resources\Page::class,
        ],
    ],
];
