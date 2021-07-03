let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/plugins/' + directory;
const dist = 'public/vendor/core/plugins/' + directory;

mix
    .sass(source + '/resources/assets/sass/toc.scss', dist + '/css')
    .copy(dist + '/css/toc.css', source + '/public/css')
    .js(source + '/resources/assets/js/toc.js', dist + '/js')
    .copy(dist + '/js/toc.js', source + '/public/js');
