const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .webpackConfig({
    stats: {
      // children: true,
    }
  })
  .js('resources/js/app.js', 'public/js')
  // .vue({ version: 2 })
  .sass('resources/sass/app.scss', 'public/css')
  .js('resources/js/admin.js', 'public/js/admin.js')
  .sass('resources/sass/admin.scss', 'public/css/admin.css')
  .version();
