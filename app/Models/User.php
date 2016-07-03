<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //所有的订阅小说
    public function novel()
    {
    	return $this->belongsToMany('App\Model\Novel', 'user_novel');
    }
}
