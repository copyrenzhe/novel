<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table = 'chapter';
    protected $fillable = ['name', 'novel_id', 'content', 'views', 'source_link'];
    //
    public function novel()
    {
    	return $this->belongsTo(Novel::class);
    }
}
