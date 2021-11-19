let mix = require('laravel-mix');

const publicPath = 'public/vendor/core/plugins/real-estate';
const resourcePath = './platform/plugins/real-estate';

mix
    .sass(resourcePath + '/resources/assets/sass/real-estate.scss', publicPath + '/css')
    .copy(publicPath + '/css/real-estate.css', resourcePath + '/public/css')

.js(resourcePath + '/resources/assets/js/currencies.js', publicPath + '/js')
    .copy(publicPath + '/js/currencies.js', resourcePath + '/public/js')

    .sass(resourcePath + '/resources/assets/sass/currencies.scss', publicPath + '/css')
    .copy(publicPath + '/css/currencies.css', resourcePath + '/public/css')

.js(resourcePath + '/resources/assets/js/property.js', publicPath + '/js')
    .copy(publicPath + '/js/property.js', resourcePath + '/public/js');

mix
    .js(resourcePath + '/resources/assets/js/app.js', publicPath + '/js')
    .copy(publicPath + '/js/app.js', resourcePath + '/public/js');
