<?php

use App\Container\Container;
use App\PostType\PostType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

if (! function_exists('app')) {
    function app(?string $abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('asset')) {
    function asset($key, $manifest = null)
    {
        return app('assets')->manifest($manifest)->get($key);
    }
}

if (! function_exists('config')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('now')) {
    function now(): Carbon
    {
        return Carbon::now();
    }
}

if (! function_exists('collect')) {
    function collect($items): Collection
    {
        return Collection::make($items);
    }
}

if (! function_exists('postType')) {
    function postType(string $singular, ?string $plural = null): PostType
    {
        return new PostType($singular, $plural);
    }
}

if (! function_exists('socialIcons')) {
    function socialIcons(): string
    {
        ob_start();
        foreach (get_field('social_accounts', 'option') as $socialAccount) : ?>
            <a class="text-xs" href="<?= $socialAccount['social_link']; ?>" target="_blank">
                <span class="fa-stack fa-1x">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fa-stack-1x text-blue fab <?= $socialAccount['social_account']; ?>"></i>
                </span>
            </a>
        <?php endforeach;

        $icons = ob_get_contents();
        ob_end_clean();

        return $icons;
    }
}

if (! function_exists('copyright')) {
    function copyright(): string
    {
        return str_replace('{{y}}', date('Y'), str_replace('{{site}}', get_bloginfo('site'),
            get_field('copyright_text', 'option')));
    }
}

if (! function_exists('formattedAddress')) {
    function formattedAddress(): string
    {
        return str_replace(',', '<br>', get_field('full_address', 'option'));
    }
}

function compare_base_url(string $base_url, string $input_url, bool $strict_scheme = true): bool
{
    $base_url = trailingslashit($base_url);
    $input_url = trailingslashit($input_url);

    if ($base_url === $input_url) {
        return true;
    }

    $input_url = parse_url($input_url);

    if (!isset($input_url['host'])) {
        return true;
    }

    $base_url = parse_url($base_url);

    if (!isset($base_url['host'])) {
        return false;
    }

    if (!$strict_scheme || !isset($input_url['scheme']) || !isset($base_url['scheme'])) {
        $input_url['scheme'] = $base_url['scheme'] = 'soil';
    }

    if (($base_url['scheme'] !== $input_url['scheme'])) {
        return false;
    }

    if ($base_url['host'] !== $input_url['host']) {
        return false;
    }

    if ((isset($base_url['port']) || isset($input_url['port']))) {
        return isset($base_url['port'], $input_url['port']) && $base_url['port'] === $input_url['port'];
    }

    return true;
}

function is_production_environment(): bool
{
    if (defined('WP_ENV')) {
        return \WP_ENV === 'production';
    }

    if (function_exists('wp_get_environment_type')) {
        return wp_get_environment_type() === 'production';
    }

    return true;
}
