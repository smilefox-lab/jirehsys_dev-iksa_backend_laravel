let mix = require('laravel-mix');

const resourcePath = 'platform/themes/flex-home';
const publicPath = 'public/themes/flex-home';

mix
    .sass(resourcePath + '/assets/sass/style.scss', publicPath + '/css')
    .copy(publicPath + '/css/style.css', resourcePath + '/public/css')
    .js(resourcePath + '/assets/js/app.js', publicPath + '/js')
    .copy(publicPath + '/js/app.js', resourcePath + '/public/js')
    .js(resourcePath + '/assets/js/components.js', publicPath + '/js')
    .copy(publicPath + '/js/components.js', resourcePath + '/public/js');
