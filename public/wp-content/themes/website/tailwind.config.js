module.exports = {
    mode: 'jit',
    purge: [
        './*.php',
        './inc/*.php',
        './templates/*.php',
        './assets/**/*.{js,jsx,ts,tsx,vue}'
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        colors: {
            transparent: 'transparent',
            black: '#222222',
            white: '#ffffff',
        },
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
}
