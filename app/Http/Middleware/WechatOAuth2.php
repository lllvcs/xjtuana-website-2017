<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Auth\AuthenticationException;
use App\Models\User;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;

class WechatOAuth2
{
    use WechatComponent;
    
    /**
     * Handle an incoming request.
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
                return $this->error('来源错误', '请从微信公众号进行访问！');
            }
        
            $response = $this->wechat->oauth2->accessToken($request->code);
            
            if (isset($response['errcode']) && 0 !== $response['errcode']) {
                return $this->error('来源错误', '请从微信公众号进行访问！');
            }
            
            $user = User::whereHas('wechat_account', function ($query) use ($response) {
                $query->where('openid', '=', $response['openid']);
            })->first();
            
            if (!$user) {
                return $this->error('尚未绑定NetID', '您还没有绑定NetID！');
            }
            
            $request->session()->regenerate();
            Auth::login($user, true);
        }
        
        if (!$request->user()->wechat_account) {
            return $this->error('尚未绑定NetID', '您还没有绑定NetID！');
        }
    
        return $next($request);
    }
    
    /**
     * Rendor an error
     *
     * @param  string  $title
     * @param  string  $message
     * @return \Illuminate\Http\Response;
     */
    protected function error($title, $message) {
        return response()->view('wechat.msg.error', [
            'title' => $title,
            'message' => $message,
        ]);
    }
    
}
