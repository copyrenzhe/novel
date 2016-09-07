<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';

    protected $fillable = ['username', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
