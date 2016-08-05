<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    protected $table = 'novel';
    protected $fillable = ['name', 'description', 'author_id', 'type', 'cover', 'hot', 'sort', 'is_over', 'biquge_url', 'chapter_num'];

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

    public function scopeTop($query)
    {
        return $query->orderBy('sort', 'desc');
    }

    //热门
    public function scopeHot($query)
    {
        return $query->orderBy('hot', 'desc');
    }

    //最新
    public function scopeLast($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    //周热门
    public function scopeWeekHot($query)
    {
        return $query->whereExists(function ($query) {
            $query
                ->leftJoin('chapter', 'novel.id', '=', 'chapter.novel_id')
                ->select(DB::raw('count(views) as views'))
                ->from('chapter')
                ->whereRaw('chapter.novel_id=novel.id');
            })
            ->take(8);
    }

    //月热门
    public function scopeMonthHot($query)
    {
        return $query->take(8);
    }
}
