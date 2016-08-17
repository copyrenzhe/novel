<?php

namespace App\Http\Middleware;

use Closure;

class WechatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_weixin()) {
            //如果是服务号
            if(env('WECHAT_TYPE', 'SUB')=='SRV') {
                $wechat = app('wechat');
                $response = $wechat->oauth->scopes(['snsapi_userinfo'])
                    ->setRequest($request)
                    ->redirect();
                return $response;
            }
        }
        return $next($request);
    }
}
