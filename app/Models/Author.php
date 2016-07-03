<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    //
    public function novel()
    {
    	return $this->hasMany('App\Model\Novel');
    }
}
