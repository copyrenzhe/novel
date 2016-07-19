<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    protected $table = 'novel';
    protected $fillable = ['name', 'description', 'author_id', 'type', 'cover', 'hot', 'sort', 'is_over', 'biquge_url'];

    //
    public function tags()
    {
    	return $this->belongsToMany('App\Models\Tag', 'novel_tag');
    }

    public function author()
    {
    	return $this->belongsTo('App\Models\Author');
    }

    public function chapter()
    {
    	return $this->hasMany('App\Models\Chapter');
    }

    //该小说订阅用户
    public function user()
    {
    	return $this->belongsToMany('App\Models\User', 'user_novel');
    }

    public function scopeContinued($query)
    {
        return $query->where('is_over', '=', 0);
    }

    public function scopeOver($query)
    {
        return $query->where('is_over', '=', 1);
    }
}
