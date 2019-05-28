<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Auth\AuthenticationException;
use App\Models\User;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;

class WechatLogin
{
    use WechatComponent;
    
    /**
     * Handle an incoming request.``
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            if (!$request->has(['code', 'state']) || $request->state !== $this->wechat->config('oauth2.state')) {
                return $next($request);
            }
        
            $response = $this->wechat->oauth2->accessToken($request->code);
            
            if (isset($response['errcode']) && $response['errcode'] !== 0) {
                return $next($request);
            }
            
            $user = User::whereHas('wechat_account', function ($query) use ($response) {
                $query->where('openid', '=', $response['openid']);
            })->first();
            
            if (!$user) {
                return $next($request);
            }
            
            $request->session()->regenerate();
            Auth::login($user, true);
        }
    
        return $next($request);
    }
}
