<?php

namespace App;

class Theme
{
    protected static $instance;

    public static function instance(): self
    {
        if (is_null(static::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->hooks();
        $this->loadPlugins();
    }

    protected function hooks()
    {
        add_action('after_setup_theme', [$this, 'themeSupport']);
        add_action('after_setup_theme', [$this, 'registerMenus']);
        add_action('after_setup_theme', [$this, 'registerSidebars']);
        add_action('wp_enqueue_scripts', [$this, 'scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'scripts']);

        add_filter('script_loader_src', [$this, 'removeScriptVersion'], 15, 1);
        add_filter('style_loader_src', [$this, 'removeScriptVersion'], 15, 1);

        add_action('wp_enqueue_scripts', [$this, 'jsToFooter']);

        add_action('init', [$this, 'headCleanup']);
        add_action('widgets_init', [$this, 'registerWidgets']);
        add_filter('the_generator', '__return_false');
        add_filter('language_attributes', [$this, 'languageAttributes']);
        add_filter('style_loader_tag', [$this, 'cleanStyleTag']);
        add_filter('get_bloginfo_rss', [$this, 'removeDefaultDescription']);
        add_filter('get_avatar', [$this, 'removeSelfClosingTags']);
        add_filter('comment_id_fields', [$this, 'removeSelfClosingTags']);
        add_filter('post_thumbnail_html', [$this, 'removeSelfClosingTags']);
        add_filter('embed_oembed_html', [$this, 'embedWrap']);
        add_filter('body_class', [$this, 'bodyClass']);
        if (! is_admin()) {
            add_filter('script_loader_tag', [$this, 'cleanScriptTag']);
        }
    }

    protected function loadPlugins()
    {
        define('MY_ACF_PATH', get_stylesheet_directory().'/inc/plugins/acf-pro/');
        define('MY_ACF_URL', get_stylesheet_directory_uri().'/inc/plugins/acf-pro/');

        include_once(MY_ACF_PATH.'acf.php');

        add_filter('acf/settings/url', function ($url) {
            return MY_ACF_URL;
        });
    }

    public function autoloadDirectory(string $directory): void
    {
        collect(glob($directory.'/*.php'))
            ->each(fn($file) => include_once $file);
    }

    public function themeSupport(): void
    {
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
    }

    public function registerMenus(): void
    {
        register_nav_menus(
            [
                'main-menu' => esc_html__('Main Menu', ''),
                'mobile-menu' => esc_html__('Mobile Menu', ''),
                'top-menu' => esc_html__('Top Menu', ''),
                'footer-menu' => esc_html__('Footer Menu', ''),
            ]
        );
    }

    public function registerSidebars(): void
    {
        register_sidebar([
            'name' => 'Footer Sidebar 1',
            'id' => 'footer_1',
            'description' => '',
            'class' => '',
            'before_widget' => '<div class="mt-12">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="mb-8 text-yellow text-xl">',
            'after_title' => '</h3>',
        ]);
        register_sidebar([
            'name' => 'Footer Sidebar 2',
            'id' => 'footer_2',
            'description' => '',
            'class' => '',
            'before_widget' => '<div>',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="mb-8 text-yellow text-xl">',
            'after_title' => '</h3>',
        ]);
        register_sidebar([
            'name' => 'Footer Sidebar 3',
            'id' => 'footer_3',
            'description' => '',
            'class' => '',
            'before_widget' => '<div>',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="mb-8 text-yellow text-xl">',
            'after_title' => '</h3>',
        ]);
        register_sidebar([
            'name' => 'Footer Sidebar 4',
            'id' => 'footer_4',
            'description' => '',
            'class' => '',
            'before_widget' => '<div class="footer-blog">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="mb-8 text-yellow text-xl">',
            'after_title' => '</h3>',
        ]);
    }

    public function registerWidgets()
    {
//        register_widget('');
    }

    public function scripts(): void
    {
        wp_enqueue_style(
            'fonts',
            'https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap',
            [],
            null
        );
        wp_enqueue_style('style', get_template_directory_uri().'/public/css/front.css', [], null);
        wp_register_script('maps',
            'https://maps.googleapis.com/maps/api/js?key='.get_option('options_map_api_key').'&callback=initMap&libraries=&v=weekly');
        wp_script_add_data('maps', 'defer', true);
        wp_enqueue_script('manifest', get_template_directory_uri().'/public/js/manifest.js', [], null, true);
        wp_script_add_data('manifest', 'async', true);
        wp_enqueue_script('vendor', get_template_directory_uri().'/public/js/vendor.js', [], null, true);
        wp_script_add_data('vendor', 'async', true);
        wp_enqueue_script('script', get_template_directory_uri().'/public/js/front.js', [], null, true);
        wp_script_add_data('script', 'async', true);
        wp_enqueue_script('fontawesome', '//kit.fontawesome.com/baa20943ee.js', [], null);
        wp_script_add_data('fontawesome', 'crossorigin', 'anonymous');
    }

    public function removeScriptVersion(string $src): string
    {
        return $src ? esc_url(remove_query_arg('ver', $src)) : false;
    }

    public static function jsToFooter(): void
    {
        remove_action('wp_head', 'wp_print_scripts');
        remove_action('wp_head', 'wp_print_head_scripts', 9);
        remove_action('wp_head', 'wp_enqueue_scripts', 1);
    }

    public function headCleanup(): void
    {
        remove_action('wp_head', 'feed_links_extra', 3);
        add_action('wp_head', 'ob_start', 1, 0);
        add_action('wp_head', function () {
            $pattern = '/.*'.preg_quote(esc_url(get_feed_link('comments_'.get_default_feed())), '/').'.*[\r\n]+/';
            echo preg_replace($pattern, '', ob_get_clean());
        }, 3, 0);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('use_default_gallery_style', '__return_false');
        add_filter('emoji_svg_url', '__return_false');
        add_filter('show_recent_comments_widget_style', '__return_false');
    }

    public function languageAttributes(): string
    {
        $attributes = [];

        if (is_rtl()) {
            $attributes[] = 'dir="rtl"';
        }

        $lang = get_bloginfo('language');

        if ($lang) {
            $attributes[] = "lang=\"$lang\"";
        }

        $output = implode(' ', $attributes);

        return $output;
    }

    function cleanStyleTag(string $input): string
    {
        preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input,
            $matches);
        if (empty($matches[2])) {
            return $input;
        }
        // Only display media if it is meaningful
        $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="'.$matches[3][0].'"' : '';

        return '<link rel="stylesheet" href="'.$matches[2][0].'"'.$media.'>'."\n";
    }

    function cleanScriptTag(string $input): string
    {
        $input = str_replace("type='text/javascript' ", '', $input);
        $input = \preg_replace_callback(
            '/document.write\(\s*\'(.+)\'\s*\)/is',
            function ($m) {
                return str_replace($m[1], addcslashes($m[1], '"'), $m[0]);
            },
            $input
        );

        return str_replace("'", '"', $input);
    }

    public function bodyClass(array $classes): array
    {
        // Add post/page slug if not present
        if (is_single() || is_page() && ! is_front_page()) {
            if (! in_array(basename(get_permalink()), $classes)) {
                $classes[] = basename(get_permalink());
            }
        }

        // Remove unnecessary classes
        $home_id_class = 'page-id-'.get_option('page_on_front');
        $remove_classes = [
            'page-template-default',
            $home_id_class
        ];
        $classes = array_diff($classes, $remove_classes);

        return $classes;
    }

    public function embedWrap($cache): string
    {
        return '<div class="entry-content-asset">'.$cache.'</div>';
    }

    public function removeSelfClosingTags(string $input): string
    {
        return str_replace(' />', '>', $input);
    }

    public function removeDefaultDescription(string $bloginfo): string
    {
        $default_tagline = 'Just another WordPress site';

        return ($bloginfo === $default_tagline) ? '' : $bloginfo;
    }
}
