<?php

return [

    'name' => env('APP_NAME', wp_get_theme()->get('Name')),

    'env' => defined('WP_ENV') ? WP_ENV : env('WP_ENV', 'production'),

    'timezone' => get_option('timezone_string', 'GMT'),

    'locale' => get_locale(),

    'providers' => [
        \App\Providers\FilesystemServiceProvider::class,
        \App\Providers\ThemeServiceProvider::class,
        \App\Assets\AssetsServiceProvider::class,
        \App\Providers\CleanupServiceProvider::class,
        \App\Providers\DisableAssetVersioningProvider::class,
//        \App\Providers\DisableRestApiProvider::class,
        \App\Providers\DisableTrackbacksProvider::class,
        \App\Providers\JsToFooterProvider::class,
        \App\Providers\NiceSearchProvider::class,
        \App\Providers\RelativeUrlsProvider::class,
    ],

];
