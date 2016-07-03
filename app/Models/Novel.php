<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    //
    public function tags()
    {
    	return $this->belongsToMany('App\Model\Tag', 'novel_tag');
    }

    public function author()
    {
    	return $this->belongsTo('App\Model\Author');
    }

    public function chapter()
    {
    	return $this->hasMany('App\Model\Chapter');
    }

    //该小说订阅用户
    public function user()
    {
    	return $this->belongsToMany('App\Model\User', 'user_novel');
    }
}
