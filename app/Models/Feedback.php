<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    //
    protected $table = 'feedback';
    protected $fillable = ['title', 'url', 'name', 'email', 'content'];
}
