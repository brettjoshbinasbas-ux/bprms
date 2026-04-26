<?php

return [
    'defaults' => [
        'guard' => 'resident',
        'passwords' => 'residents',
    ],

    'guards' => [
        'resident' => [
            'driver' => 'session',
            'provider' => 'residents',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'residents' => [
            'driver' => 'eloquent',
            'model' => App\Models\Resident::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'residents' => [
            'provider' => 'residents',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
