<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNovel extends Model
{
    //
    protected $table = 'user_novel';
    public $timestamps = false;

    protected $fillable =['user_id', 'novel_id'];
}
