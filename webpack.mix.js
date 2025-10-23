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

const webpack = require('webpack');

mix.webpackConfig({
  resolve: {
    alias: {
      'process/browser': 'process/browser.js',
    },
  },
  plugins: [
    new webpack.ProvidePlugin({
      process: 'process/browser',
    }),
  ],
});



mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/carrito/index.js', 'public/js/carrito')
    .js('resources/js/carrito/carrito-service.js', 'public/js/carrito-service')
    .js('resources/js/productos/producto-service.js', 'public/js/producto')
    // .js('resources/js/shared/listado-productos.js', 'public/js/shared')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
