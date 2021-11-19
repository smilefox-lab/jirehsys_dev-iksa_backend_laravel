let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/location';
const source = './platform/plugins/location';

mix
    .js(source + '/resources/assets/js/location.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
