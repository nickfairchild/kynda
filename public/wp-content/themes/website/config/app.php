<?php

return [

    'name' => env('APP_NAME', wp_get_theme()->get('Name')),

    'env' => defined('WP_ENV') ? WP_ENV : env('WP_ENV', 'production'),

    'timezone' => get_option('timezone_string', 'GMT'),

    'locale' => get_locale(),

    'providers' => [
        \Kynda\Providers\FilesystemServiceProvider::class,
        \Kynda\Providers\ThemeServiceProvider::class,
        \Kynda\Assets\AssetsServiceProvider::class,
        \Kynda\Providers\CleanupServiceProvider::class,
        \Kynda\Providers\DisableAssetVersioningProvider::class,
//        \Kynda\Providers\DisableRestApiProvider::class,
        \Kynda\Providers\DisableTrackbacksProvider::class,
        \Kynda\Providers\JsToFooterProvider::class,
        \Kynda\Providers\NiceSearchProvider::class,
//        \Kynda\Providers\RelativeUrlsProvider::class,
        \NickFairchild\Backup\BackupServiceProvider::class,
    ],

];
