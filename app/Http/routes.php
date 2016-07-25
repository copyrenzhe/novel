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

    Route::get('author/{authorId}', 'AuthorController@info');

    Route::get('authors', 'AuthorController@all');

    Route::get('over-novel', 'IndexController@over');

    Route::get('search/{bookName}', 'IndexController@search');

    Route::get('book/{bookId}', 'BookController@index');

    Route::get('book/{bookId}/{chapterId}', 'BookController@chapter');

    Route::get('{category}', ['uses'=>'IndexController@category'])
        ->where('category', '(xuanhuan|xiuzhen|dushi|lishi|wangyou|kehuan)');

});

Route::group(['prefix'=>'biquge'], function() {
   Route::get('over/{page}', function($page){
       $page_size = 500;
       $offset = ($page-1)*$page_size;
       $novels = \App\Models\Novel::over()->skip($offset)->take($page_size)->get();
       foreach ($novels as $novel) {
           \App\Repositories\Snatch\Biquge::update($novel);
       }
       echo "完本小说从{$offset}到{($offset+$page_size)}获取完毕<br/>";
   })->where('page', '[0-9]+');

    Route::get('continued/{page}', function ($page) {
        $page_size = 1000;
        $offset = ($page - 1) * $page_size;
        $novels = \App\Models\Novel::continued()->skip($offset)->take($page_size)->get();
        foreach ($novels as $novel) {
            \App\Repositories\Snatch\Biquge::update($novel);
        }
        echo "更新小说从{$offset}到{($offset+$page_size)}获取完毕<br/>";
    })->where('page', '[0-9]+');
});

//wechat route
Route::any('/wechat', 'WechatController@serve');

//wechat user
Route::get('/users', 'UserController@users');
Route::get('/user/{openId}', 'UserController@user');