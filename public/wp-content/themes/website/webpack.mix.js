const mix = require('laravel-mix')

mix
    .setPublicPath('public')
    .js('assets/js/front.js', 'public/js')
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
