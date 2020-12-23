<?php

if (! function_exists('now')) {
    function now()
    {
        return \Illuminate\Support\Carbon::now();
    }
}

if (! function_exists('collect')) {
    function collect($items)
    {
        return \Illuminate\Support\Collection::make($items);
    }
}

if (! function_exists('postType')) {
    function postType(string $singular, ?string $plural = null)
    {
        return new App\PostType\PostType($singular, $plural);
    }
}

if (! function_exists('socialIcons')) {
    function socialIcons()
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
    function formattedAddress()
    {
        return str_replace(',', '<br>', get_field('full_address', 'option'));
    }
}
