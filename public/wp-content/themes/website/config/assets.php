<?php

return [

    'default' => 'theme',

    'manifests' => [
        'theme' => [
            'strategy' => 'relative',
            'path' => get_theme_file_path('/public'),
            'uri' => get_theme_file_uri('/public'),
            'manifest' => get_theme_file_path('/public/mix-manifest.json'),
        ]
    ]

];
