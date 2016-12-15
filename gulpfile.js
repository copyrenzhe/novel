var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    // mix.less('app.less');
    // mix.less('admin-lte/AdminLTE.less');
    // mix.less('bootstrap/bootstrap.less');

    mix
        .styles([
            'style.css',
            'main.css'
        ], 'public/dist/css')

        .scripts([
            'jquery.js',
            'underscore.js',
            'vendor/jstorage/jstorage.min.js',
            'santruyen.js',
            'functions.js',
            'vendor/libs/*.js',
            'vendor/modules/*.js',
            'vendor/pages/*.js'
        ], 'public/dist/js')

        .version([
            'dist/css/all.css',
            'dist/js/all.js'
        ])

        .copy([
            'resources/assets/images'
        ], 'public/build/dist/images')
});
