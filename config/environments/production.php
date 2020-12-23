<?php

use function Env\env;

define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
define('DISALLOW_FILE_MODS', true);

define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', env('WP_DEBUG_LOG') ?? false);
define('SCRIPT_DEBUG', false);
ini_set('display_errors', '0');
