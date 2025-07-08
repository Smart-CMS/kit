<?php

return [
    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
    ],
    'providers' => [
        'admin' => [
            'driver' => 'eloquent',
            'model' => SmartCms\Kit\Models\Admin::class,
        ],
    ],
];
