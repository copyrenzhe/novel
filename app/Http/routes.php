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



Route::get('test', function() {
    return App\Repositories\Snatch\Biquge::init();
});


Route::group(['middleware'=>['web']], function(){

    Route::get('/', 'IndexController@index');

    Route::get('top-novel', 'IndexController@top');

    Route::get('authors', 'AuthorController@novelList');

    Route::get('over-novel', 'IndexController@over');

    Route::get('search/{bookName}', 'IndexController@search');

    Route::get('book/{bookId}', 'BookController@index');

    Route::get('{category}', ['uses'=>'IndexController@category'])
        ->where('category', '(xuanhuan|xiuzhen|dushi|lishi|wangyou|kehuan)');

});



//wechat route
Route::any('/wechat', 'WechatController@serve');

//wechat user
Route::get('/users', 'UserController@users');
Route::get('/user/{openId}', 'UserController@user');