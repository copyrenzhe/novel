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
    $dtStart = microtime_float();
    $novel = \App\Models\Novel::find(5125);
        \App\Repositories\Snatch\Biquge::updateNew($novel);
    $dtEnd = microtime_float();
    echo "耗时：".($dtEnd-$dtStart)."秒";
});


Route::group(['middleware'=>['web']], function(){

    Route::get('/', 'IndexController@index');

    Route::get('new-release', 'IndexController@newRelease');

    Route::get('top-novel', 'IndexController@top');

    Route::get('author/{authorId}', 'AuthorController@info');

    Route::get('authors', 'AuthorController@all');

    Route::get('over-novel', 'IndexController@over');

    Route::get('search/{bookName}', 'IndexController@search');



    Route::get('{category}', ['uses'=>'IndexController@category'])
        ->where('category', '(xuanhuan|xiuzhen|dushi|lishi|wangyou|kehuan)');

});

Route::group(['prefix'=>'book', 'middleware' => ['web','wechat.oauth']], function() {
    Route::get('/{bookId}', 'BookController@index');
    Route::get('/{bookId}/{chapterId}', 'BookController@chapter');
});

//wechat route
Route::any('wechat', 'WechatController@serve');

//wechat user
Route::get('users', 'UserController@users');
Route::get('user/{openId}', 'UserController@user');