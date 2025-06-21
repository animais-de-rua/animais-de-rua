<?php

return [
    'colors' => [
        ['#283240', '40, 50, 64'],
        ['#702f39', '112, 47, 57'], // red
        // ['#4171b7', '65, 113, 183'], // blue
        // ['#b26a00', '178, 106, 0'], // orange
    ],

    'auto_admin_domains' => [
        //
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
