<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;
use App\Models\User;
use Cache;

class WechatController extends Controller
{
    use WechatComponent;
    
    public function verify() {
        return $this->wechat->config('oauth2.verify');
    }
    
    public function listen(Request $request) {
        
        $this->wechat->server->setHandler(function($message) {
            
            $openid = $message->get('FromUserName');
            $user = User::whereHas('wechat_account', function ($query) use ($openid) {
                $query->where('openid', '=', $openid);
            })->first();
            if (!$user) {
                $url = $this->wechat->oauth2->getAuthorizeUrl(route('wechat.bind'), true);
                return "还没有进行微信绑定噢\n\n" . '<a href="' . $url . '">点此绑定NetID</a>';
            } else {
                return '已经绑定NetID: ' . $user->netid . "\n" . '请通过菜单栏使用功能~';
            }
            
            switch($message->MsgType) {
                case 'text':
                    return $this->textHandler($message);
                    break;
                case 'event':
                    return $this->eventHandler($message);
                    break;
                default:
                    return '默认处理器';
                    break;
            }
        });

        return $this->wechat->server->handle($request);
    }
    
    /**
     * 文本消息处理器
     * 
     */
    protected function textHandler($message) {
        return '文本消息处理器';
    }
    
    /**
     * 事件处理器
     * 
     */
    protected function eventHandler($message) {
        switch ($message['Event']) {
            // 关注事件
            case 'subscribe':
                $url = $this->wechat->oauth2->getAuthorizeUrl(route('wechat.bind'), true);
                return "欢迎关注西交网管会！\n本号为服务号，提供各种微信便民功能。\n想要获取校园网资讯、1x密码等，请关注我们的订阅号。\n\n使用本号功能，请<a href=\"{$url}\">点此绑定NETID</a>";
                break;
            // 菜单点击事件
            case 'CLICK':
                return $this->menuClickHandler($message);
                break;
            
            default:
                return;
                break;
        }
        
    }
    
    /**
     * 点击菜单栏事件处理器
     * 
     */
    protected function menuClickHandler($message) {
        switch ($message['EventKey']) {
            // 点击网络报修按钮
            case 'MENU_SERVICE_NETWORK_REPAIR':
                $url = route('wechat.home');
                return [
                    'Content' => "<a href=\"{$url}\">点击进入网络报修</a>",
                ];
                break;
            
            default:
                return;
                break;
        }
    }
    
}
