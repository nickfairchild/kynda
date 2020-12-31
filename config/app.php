<?php

use function Env\env;

$rootDir = dirname(__DIR__);

$webRootDir = $rootDir.'/public';

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable($rootDir);
if (file_exists($rootDir.'/.env')) {
    $dotenv->load();
    $dotenv->required(['WP_HOME', 'WP_SITEURL', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']);
}

/** Environment */
define('WP_ENV', env('WP_ENV') ?: 'production');
define('WP_DEFAULT_THEME', env('WP_DEFAULT_THEME') ?: 'website');

/** URLs */
define('WP_HOME', env('WP_HOME'));
define('WP_SITEURL', env('WP_SITEURL'));

/** Custom Content Directory */
define('CONTENT_DIR', '/wp-content');
define('WP_CONTENT_DIR', $webRootDir.CONTENT_DIR);
if (isset($_SERVER['HTTP_HOST']) && ! defined('WP_CONTENT_URL')) {
    $protocol = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
    define('WP_CONTENT_URL', "{$protocol}://{$_SERVER['HTTP_HOST']}".CONTENT_DIR);
}

/** DB Settings */
define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_HOST', env('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

/** Authentication Unique Keys and Salts */
define('AUTH_KEY', env('AUTH_KEY'));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
define('NONCE_KEY', env('NONCE_KEY'));
define('AUTH_SALT', env('AUTH_SALT'));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

$envConfig = __DIR__.'/environments/'.WP_ENV.'.php';
if (file_exists($envConfig)) {
    require_once $envConfig;
}

/** Bootstrap WordPress */
if (! defined('ABSPATH')) {
    define('ABSPATH', $webRootDir.'/wp/');
}
