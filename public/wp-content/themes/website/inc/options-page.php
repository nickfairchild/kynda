<?php

add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        $optionPage = acf_add_options_page([
            'page_title' => 'Theme General Settings',
            'menu_title' => __('Theme Settings'),
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
        ]);

        acf_add_options_page(array(
            'page_title' => __('Social Settings'),
            'menu_title' => __('Social'),
            'parent_slug' => $optionPage['menu_slug'],
        ));

//        acf_add_options_page(array(
//            'page_title' => __('Map Settings'),
//            'menu_title' => __('Map'),
//            'parent_slug' => $optionPage['menu_slug'],
//        ));
    }
});
