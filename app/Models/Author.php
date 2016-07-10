<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    //
    protected $table = 'author';
    protected $fillable = ['name'];
    public $timestamps = false;


    public function novel()
    {
    	return $this->hasMany('App\Model\Novel');
    }
}
