<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    protected $fillable =['open_id', 'nickname', 'is_subscribe', 'push_time'];
    
    //所有的订阅小说
    public function novel()
    {
    	return $this->belongsToMany(Novel::class, 'user_novel');
    }
}
