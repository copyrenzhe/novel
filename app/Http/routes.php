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

    Route::get('new-releases', ['as' => 'release','uses' => 'IndexController@newRelease']);

    Route::get('top-novels', ['as' => 'top', 'uses' => 'IndexController@top']);

    Route::get('over-novels', ['as' => 'over', 'uses' => 'IndexController@over']);

    Route::get('authors/{authorId}', ['as' => 'author', 'uses' => 'AuthorController@info']);

    Route::get('authors', ['as' => 'authors', 'uses' => 'AuthorController@all']);

    Route::get('search?keyword={keywords}', ['as' => 'search', 'uses' => 'IndexController@search']);

    Route::get('{category}', ['as' => 'category', 'uses'=>'IndexController@category'])
        ->where('category', '(xuanhuan|xiuzhen|dushi|lishi|wangyou|kehuan)');

    Route::get('feedback', 'IndexController@feedback');
    Route::post('feedback', 'IndexController@postFeedback');

});

Route::group(['prefix'=>'books/{bookId}', 'middleware' => ['web','wechat']], function() {
    Route::get('/{openId?}', ['as' => 'book', 'uses' => 'BookController@index'])
        ->where(['bookId'=> '[0-9]+', 'openId' => '[a-zA-Z]+']);
    Route::get('/chapters/{chapterId}/{openId?}', ['as' => 'chapter', 'uses' => 'BookController@chapter'])
        ->where(['bookId'=> '[0-9]+', 'chapterId' => '[0-9]+', 'openId' => '[a-zA-Z]+']);
});

//wechat route
Route::any('wechat', 'WechatController@serve');

//wechat user
Route::get('users', 'UserController@users');
Route::get('user/{openId}', 'UserController@user');