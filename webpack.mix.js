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

mix
  .js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .babel('resources/assets/js/sw.js', 'public/sw.js')

  .js('resources/assets/js/admin/reports.js', 'public/js/admin')
  .sass('resources/assets/sass/admin/reports.scss', 'public/css/admin')
  .version();
