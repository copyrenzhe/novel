'use strict';

const elixir = require('laravel-elixir');
const gulp = require('gulp');
const config = require('./node-config');
const path = require('path');
const request = require('request');
const chalk = require('chalk');
const fs = require('fs');

// 检查文件夹是否存在,若不存在则创建
function checkDirectory(dirPath, callback) {
    callback = callback || function (){};
    fs.exists(dirPath, (exists) => {
        exists ? callback() : fs.mkdir(dirPath, 777, err => {
                if(err) {
                    console.log(chalk.red('创建静态化目录 %s 失败！请确认有写入权限。'), dirPath);
                } else {
                    callback();
                }
            });
    });
}

const staticDir = './storage/static';
const staticFile = 'index.html';
const staticPath = path.join(staticDir, staticFile);

gulp.task('static', () => {
    checkDirectory(staticDir, () => {
        request(config.apps[0].env.NODE_SITE).pipe(fs.createWriteStream(staticPath));
    });
});

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
