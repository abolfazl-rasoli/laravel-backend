const mix = require('laravel-mix');

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

const adminRoot = './modules/Admin/';
mix.react( adminRoot + 'Resources/js/admin/app.js', 'assets/admin/admin.js')
    .sass( adminRoot + 'Resources/sass/admin.scss', 'assets/admin');
