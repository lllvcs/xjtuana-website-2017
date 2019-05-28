<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;
use App\Events\Wechat\Auth\BoundEvent;
use App\Events\Wechat\Auth\UnBoundEvent;

class AuthController extends Controller
{
    use WechatComponent;

    public function bind(Request $request) {
        
        if ($request->user()->wechat_account) {
            return view('wechat.msg.success', [
                'title' => '已经绑定NetID',
                'message' => 'NetID: ' . $request->user()->netid,
            ]);
        }
        
        $response = $this->wechat->oauth2->accessToken($request->code);
        
        if (isset($response['errcode']) && 0 !== $response['errcode']) {
            return view('wechat.msg.error', [
                'title' => '绑定出错',
                'message' => '请求已过期，请重新绑定',
            ]);
        }
        
        $response = $this->wechat->oauth2->userinfo($response->get('access_token'), $response->get('openid'));
        
        // Write the openid into session temporary
        $request->session()->flash('wechat.tmp.openid', $response->get('openid'));
        $request->session()->flash('wechat.tmp.nickname', $response->get('nickname'));

        $data = [
            'wechat' => [
                'nickname' => $response->get('nickname'),
                'avator' => $response->get('headimgurl'),
            ],
            'user' => [
                'netid' => $request->user()->netid,
                'name' => $request->user()->profile->name,
            ],
        ];
        return view('wechat.msg.bind', $data);
    }
    
    public function bindConfirm(Request $request) {
        
        if (!$request->session()->has(['wechat.tmp.openid', 'wechat.tmp.nickname'])) {
            return view('wechat.msg.error', [
                'title' => '绑定出错',
                'message' => '请从微信公众号进入绑定页面',
            ]);
        }
        
        $request->user()->wechat_account()->create([
            'openid' => $request->session()->get('wechat.tmp.openid'),
        ]);
        
        event(new BoundEvent($request->user()));
        
        return view('wechat.msg.success', [
            'title' => '绑定成功',
            'message' => '您已成功绑定NetID: '. $request->user()->netid,
        ]);
        
    }
    
    public function unbind(Request $request) {
        
        if (!$request->user()->wechat_account) {
            return view('wechat.msg.error', [
                'title' => '尚未绑定NetID',
                'message' => '您还没有绑定NetID',
            ]);
        }
        
        $data = [
            'user' => [
                'netid' => $request->user()->netid,
                'name' => $request->user()->profile->name,
            ],
        ];
        return view('wechat.msg.unbind', $data);
    }
    
    public function unbindConfirm(Request $request) {
        
        if (!$request->user()->wechat_account) {
            return view('wechat.msg.error', [
                'title' => '尚未绑定NetID',
                'message' => '您还没有绑定NetID',
            ]);
        }
        
        $request->user()->wechat_account->delete();
        
        event(new UnBoundEvent($request->user()));
            
        return view('wechat.msg.success', [
            'title' => '解绑成功',
            'message' => '您已成功解除绑定NetID: '. $request->user()->netid,
        ]);
    }
}
