<?php

namespace App\Http\Controllers\Wechat\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;

class UrlController extends Controller
{
    use WechatComponent;
    
    public function bind(Request $request) {
        $data = $this->wechat->oauth2->getAuthorizeUrl(route('wechat.bind'), true);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function unbind(Request $request) {
        $data = route('wechat.unbind');
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function order(Request $request) {
        $data = $this->wechat->oauth2->getAuthorizeUrl(route('user.order.create'), true);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function dashboard(Request $request) {
        $data = $this->wechat->oauth2->getAuthorizeUrl(route('dashboard.home'), true);
        return $this->jsonResponse('OK', 0, $data);
    }
}
