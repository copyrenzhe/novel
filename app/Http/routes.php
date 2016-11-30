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

Route::get('test', function(){
    $source = 'biquge';
    $class = ucfirst($source);
    dd($class::test());
});

Route::group(['prefix' => 'admin', 'middleware' => ['web'], 'namespace' => 'Admin'], function(){
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    Route::get('register', 'AuthController@getRegister');
    Route::post('register', 'AuthController@postRegister');
    Route::get('logout', 'AuthController@getLogout');
    Route::get('/', 'IndexController@index');

    Route::get('novels/datatables', 'NovelsController@datatables');
    Route::get('novels/{novel_id?}/snatchUpdate', ['as' => 'snatchUpdate', 'uses' => 'NovelsController@snatchUpdate'])
            ->where(['novel_id'=> '[0-9]+']);
    Route::get('novels/{novel_id?}/snatchRepair', ['as' => 'snatchRepair', 'uses' => 'NovelsController@snatchRepair'])
            ->where(['novel_id'=> '[0-9]+']);
    Route::get('novels/{novel_id?}/truncate', ['as' => 'truncate', 'uses' => 'NovelsController@truncate'])
            ->where(['novel_id'=>'[0-9]+']);
    Route::resource('novels', 'NovelsController');

    Route::get('system', 'SystemController@index');
    Route::get('system/updateAllNovels', 'SystemController@updateAll');
    Route::get('system/sumChapters', 'SystemController@sumChapter');
    Route::post('system/update', 'SystemController@update');
    Route::post('system/init', 'SystemController@init');
    Route::post('system/snatch', 'SystemController@snatch');
    Route::post('system/repair', 'SystemController@repair');
    Route::post('system/sumSingle', 'SystemController@sumSingle');


    Route::get('users/datatables', 'UserController@datatables');
    Route::resource('users', 'UserController');
});

Route::group(['middleware'=>['web', 'wechat']], function(){

    Route::get('/', 'IndexController@index');

    Route::get('new-releases', ['as' => 'release','uses' => 'IndexController@newRelease']);

    Route::get('top-novels', ['as' => 'top', 'uses' => 'IndexController@top']);

    Route::get('over-novels', ['as' => 'over', 'uses' => 'IndexController@over']);

    Route::get('authors/{authorId}', ['as' => 'author', 'uses' => 'AuthorController@info']);

    Route::get('authors', ['as' => 'authors', 'uses' => 'AuthorController@all']);

    Route::get('search', ['as' => 'search', 'uses' => 'IndexController@search']);

    Route::get('{category}', ['as' => 'category', 'uses'=>'IndexController@category'])
        ->where('category', '(xuanhuan|xiuzhen|dushi|lishi|wangyou|kehuan|mingzhu|other)');

    Route::get('feedback', ['as' => 'feedback', 'uses' => 'IndexController@feedback']);
    Route::post('feedback', 'IndexController@postFeedback');

    Route::get('ajax/subscribe', ['as' => 'subscribe','uses' =>'BookController@subscribe']);
});

Route::group(['prefix'=>'books/{bookId}', 'middleware' => ['web','wechat']], function() {
    Route::get('/{openId?}', ['as' => 'book', 'uses' => 'BookController@index'])
        ->where(['bookId'=> '[0-9]+', 'openId' => '[a-zA-Z0-9]+']);
    Route::get('/chapters/{chapterId}/{openId?}', ['as' => 'chapter', 'uses' => 'BookController@chapter'])
        ->where(['bookId'=> '[0-9]+', 'chapterId' => '[0-9]+', 'openId' => '[a-zA-Z]+']);
});

//sitemap
Route::get('sitemap', ['as' => 'sitemap', 'uses'=> 'SitemapsController@index']);
Route::get('sitemaps/novels', ['as' => 'sitemaps.novels', 'uses'=>'SitemapsController@novels']);
Route::get('sitemaps/category', ['as' => 'sitemaps.category', 'uses'=>'SitemapsController@category']);

//wechat route
Route::any('wechat', 'WechatController@serve');

//wechat user
Route::get('users', 'UserController@users');
Route::get('user/{openId}', 'UserController@user');