<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    protected $fillable =['open_id', 'is_subscribe'];
    
    //所有的订阅小说
    public function novel()
    {
    	return $this->belongsToMany('App\Models\Novel', 'user_novel');
    }
}
