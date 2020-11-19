const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ])
    .options({
        postCss: [
            tailwindcss('tailwind.config.js'),
        ]
    })
    .webpackConfig({
        output: {
            chunkFilename: 'js/[name].js?id=[chunkhash]',
        },
        resolve: {
            alias: {
                '@': path.resolve('resources/js'),
            }
        }
    })
    .version()
    .sourceMaps();
