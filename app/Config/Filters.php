<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'authfilter' => \App\Filters\AuthFilter::class,
    ];

    public array $globals = [
        'before' => [
         //'authfilter' => ['except' => ['/']], // Exclude login route from auth filter
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $filters = [
        'authfilter' => ['before' => ['dashboard', 'index', 'indexloggedin']],
    ];
}
