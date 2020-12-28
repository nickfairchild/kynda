<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap',
        [],
        null
    );
    wp_enqueue_style('style', asset('css/front.css')->uri(), [], null);

    if (get_option('options_map_api_key')) {
        wp_register_script('maps',
            'https://maps.googleapis.com/maps/api/js?key='.get_option('options_map_api_key').'&callback=initMap&libraries=&v=weekly');
        wp_script_add_data('maps', 'defer', true);
    }

    wp_enqueue_script('vendor', asset('js/vendor.js')->uri(), [], null, true);
    wp_script_add_data('vendor', 'async', true);
    wp_enqueue_script('script', asset('js/front.js')->uri(), ['vendor'], null, true);
    wp_script_add_data('script', 'async', true);
    wp_add_inline_script('vendor', asset('js/manifest.js')->contents(), 'before');

    wp_enqueue_script('fontawesome', '//kit.fontawesome.com/baa20943ee.js', [], null);
    wp_script_add_data('fontawesome', 'crossorigin', 'anonymous');
}, 100);

add_action('after_setup_theme', function () {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support(
        'custom-logo',
        [
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ]
    );
}, 20);

add_action('after_setup_theme', function () {
    register_nav_menus([
        'main-menu' => __('Main Menu', ''),
        'mobile-menu' => __('Mobile Menu', ''),
        'footer-menu' => __('Footer Menu', ''),
    ]);
}, 20);

add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<div class="mt-12">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="mb-8 text-xl">',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => 'Footer Sidebar 1',
        'id' => 'footer_1',
    ] + $config);
    register_sidebar([
        'name' => 'Footer Sidebar 2',
        'id' => 'footer_2',
    ] + $config);
    register_sidebar([
        'name' => 'Footer Sidebar 3',
        'id' => 'footer_3',
    ] + $config);
    register_sidebar([
        'name' => 'Footer Sidebar 4',
        'id' => 'footer_4',
    ] + $config);
});
