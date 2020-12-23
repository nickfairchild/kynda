const mix = require('laravel-mix')

mix.js('assets/js/front.js', 'public/js')
    .setPublicPath('public')
    .postCss('assets/css/front.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('postcss-nested'),
        require('autoprefixer'),
    ])
    .extract()

if (mix.inProduction()) {
    mix.version()
}
