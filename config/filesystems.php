<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => false,
            'throw' => true,
        ],
        'project_images' => [
            'driver' => 'local',
            'root' => storage_path('app/project-images'),
            'url' => env('APP_URL', 'http://localhost').'/project-media',
            'serve' => true,
            'throw' => true,
        ],
        'blog_images' => [
            'driver' => 'local',
            'root' => storage_path('app/blog-images'),
            'url' => env('APP_URL', 'http://localhost').'/blog-media',
            'serve' => true,
            'throw' => true,
        ],
        'resumes' => [
            'driver' => 'local',
            'root' => storage_path('app/resumes'),
            'url' => env('APP_URL', 'http://localhost').'/resume-media',
            'serve' => true,
            'throw' => true,
        ],
        'site_assets' => [
            'driver' => 'local',
            'root' => storage_path('app/site-assets'),
            'url' => env('APP_URL', 'http://localhost').'/site-media',
            'serve' => true,
            'throw' => true,
        ],
    ],
    'links' => [],
];
