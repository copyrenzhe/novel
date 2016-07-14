<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', function() {
    return App\Repositories\Snatch\Biquge::init();
});


Route::get('/authors', function() {
});

Route::get('/top-novel', function() {

});

Route::get('/over-novel', function() {

});

Route::get('/search', function() {

});

//novel categories
Route::get('/xuanhuan', function() {

});

Route::get('/xiuzhen', function() {

});

Route::get('/dushi', function() {

});

Route::get('/lishi', function() {

});

Route::get('/wangyou', function() {

});

Route::get('/kehuan', function() {

});