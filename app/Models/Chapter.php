<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    //
    public function novel()
    {
    	return $this->belongsTo('App\Model\Novel');
    }
}
