<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Boost Master Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable all Boost functionality - which
    | will prevent Boost's routes from being registered and will also
    | disable Boost's browser logging functionality from operating.
    |
    */

    'enabled' => env('BOOST_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Boost Browser Logs Watcher
    |--------------------------------------------------------------------------
    |
    | The following option may be used to enable or disable the browser logs
    | watcher feature within Laravel Boost. The log watcher will read any
    | errors within the browser's console to give Boost better context.
    */

    'browser_logs_watcher' => env('BOOST_BROWSER_LOGS_WATCHER', true),

    /*
    |--------------------------------------------------------------------------
    | Claude Code Integration
    |--------------------------------------------------------------------------
    |
    | Configuration for Claude Code integration and context files.
    |
    */

    'features' => [
        'claude-code' => true,
        'auto-completion' => true,
        'code-analysis' => true,
        'smart-suggestions' => true,
    ],
    
    'claude' => [
        'model' => 'claude-sonnet-4',
        'context_files' => [
            'app/Models/*.php',
            'app/Http/Controllers/*.php',
            'app/Repositories/*.php',
            'app/Services/*.php',
            'app/Policies/*.php',
            'resources/js/Components/**/*.vue',
            'resources/js/Pages/**/*.vue',
        ],
        'exclude_patterns' => [
            'vendor/*',
            'node_modules/*',
            'storage/*',
            'bootstrap/cache/*',
        ],
    ],
    
    'analysis' => [
        'enable_type_checking' => true,
        'enable_performance_hints' => true,
        'enable_security_checks' => true,
    ],

];
