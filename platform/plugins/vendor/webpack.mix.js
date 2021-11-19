let mix = require('laravel-mix');

const publicPath = 'public/vendor/core/plugins/vendor';
const resourcePath = './platform/plugins/vendor';

mix
    .js(resourcePath + '/resources/assets/js/account-admin.js', publicPath + '/js')
    .copy(publicPath + '/js/account-admin.js', resourcePath + '/public/js')

    .sass(resourcePath + '/resources/assets/sass/account-admin.scss', publicPath + '/css')
    .copy(publicPath + '/css/account-admin.css', resourcePath + '/public/css')

    .sass(resourcePath + '/resources/assets/sass/account.scss', publicPath + '/css')
    .copy(publicPath + '/css/account.css', resourcePath + '/public/css');

mix
    .js(resourcePath + '/resources/assets/js/app.js', publicPath + '/js')
    .copy(publicPath + '/js/app.js', resourcePath + '/public/js')
    .sass(resourcePath + '/resources/assets/sass/app.scss', publicPath + '/css')
    .copy(publicPath + '/css/app.css', resourcePath + '/public/css');
