<?php

declare(strict_types=1);

return [

    'client_id' => env('AVONEZ_AUTH_CLIENT_ID'),
        'client_secret' => env('AVONEZ_AUTH_CLIENT_SECRET'),
        'redirect' => env('AVONEZ_AUTH_REDIRECT_URI'),
        'host' => 'https://account.avonez.com',
        'scope' => 'user:read organizations:read',

];
