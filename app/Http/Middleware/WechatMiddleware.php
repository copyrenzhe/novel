<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\LaravelWechat\Middleware\OAuthAuthenticate;

class WechatMiddleware extends OAuthAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(is_weixin()) {
            //如果是服务号
            $wechat = app('EasyWeChat\\Foundation\\Application', [config('wechat')]);
            if(config('wechat.type')=='SRV') {

                if (!session('wechat.oauth_user')) {
                    if ($request->has('state') && $request->has('code')) {
                        session(['wechat.oauth_user' => $wechat->oauth->user()]);

                        return redirect()->to($this->getTargetUrl($request));
                    }

                    $scopes = config('wechat.oauth.scopes', ['snsapi_base']);

                    if (is_string($scopes)) {
                        $scopes = array_map('trim', explode(',', $scopes));
                    }

                    return $wechat->oauth->scopes($scopes)->redirect($request->fullUrl());
                }
            }
            $js = $wechat->js;
            view()->share('js', $js);

        }
        return $next($request);
    }
}
