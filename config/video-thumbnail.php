<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binaries
    |--------------------------------------------------------------------------
    |
    | Paths to ffmpeg nad ffprobe binaries
    |
    */

    'binaries' => [
        'ffmpeg' => env('FFMPEG', resource_path('ffmpeg/ffmpeg'.(DIRECTORY_SEPARATOR === '/' ? '' : '.exe'))),
        'ffprobe' => env('FFPROBE', resource_path('ffmpeg/ffprobe'.(DIRECTORY_SEPARATOR === '/' ? '' : '.exe'))),
    ],
];
