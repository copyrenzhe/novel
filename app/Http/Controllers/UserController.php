<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
    public wechat;

    /**
     * UserController constructor.
     * @param $user
     */
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    public function users()
    {
        return $this->wechat->user->lists();
    }

    public function user($openId)
    {
        return $this->wechat->user->get($openId);
    }

}
