<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'database'), // Using the database session driver for persistence

    'lifetime' => (int) env('SESSION_LIFETIME', 120), // Setting session lifetime to 120 minutes

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false), // Sessions do not expire on browser close

    'encrypt' => env('SESSION_ENCRYPT', false), // Session encryption setting

    'files' => storage_path('framework/sessions'), // Path for file session storage

    'connection' => env('SESSION_CONNECTION'), // Database connection for session storage

    'table' => env('SESSION_TABLE', 'sessions'), // Table for storing sessions

    'store' => env('SESSION_STORE'), // Cache store for session data

    'lottery' => [2, 100], // Session sweeping lottery chance

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ), // Session cookie name

    'path' => env('SESSION_PATH', '/'), // Cookie path

    'domain' => env('SESSION_DOMAIN'), // Cookie domain

    'secure' => env('SESSION_SECURE_COOKIE', false), // HTTPS only cookies setting

    'http_only' => env('SESSION_HTTP_ONLY', true), // HTTP access only setting

    'same_site' => env('SESSION_SAME_SITE', 'lax'), // Same-site cookie setting

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false), // Partitioned cookie setting

];
