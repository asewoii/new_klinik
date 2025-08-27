<?php

use App\Models\User;
use App\Models\Pasien;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [ // Admin & Dokter
            'driver' => 'session',
            'provider' => 'users',
        ],

        'pasien' => [ // Pasien
            'drive' => 'session',
            'provider' => 'pasiens',
        ]
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],

        'pasiens' => [
            'drive' => 'eloquent',
            'model' => Pasien::class,
        ]
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
