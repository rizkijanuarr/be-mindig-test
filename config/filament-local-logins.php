<?php

use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;

return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED',
                env('APP_ENV') === 'local' || env('APP_ENV') === 'production'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
            'login_page' => LoginPage::class,
        ],
    ],
];
