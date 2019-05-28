<?php

namespace App\Http\Controllers\Wechat\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Requests\API\Wechat\Menu\ViewRequest;
// use App\Http\Requests\API\Wechat\Menu\UpdateRequest;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;

class MenuController extends Controller
{
    use WechatComponent;
    
    public function index(Request $request) {
        $data = $this->wechat->menu->get();
        return $this->jsonResponse('OK', 0, $data);
    }

    public function update(Request $request) {
        $button = [
            [
                'name' => '微信绑定',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '绑定NetID',
                        'url'  => $this->wechat->oauth2->getAuthorizeUrl(route('wechat.bind'), true),
                    ],
                    [
                        'type' => 'view',
                        'name' => '解除绑定',
                        'url'  => route('wechat.unbind'),
                    ],
                ],
            ],
            [
                'name' => '校园服务',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '网络故障报修',
                        'url'  => $this->wechat->oauth2->getAuthorizeUrl(route('wechat.home') . '#/order', true),
                    ],
                    [
                        'type' => 'view',
                        'name' => '个人信息',
                        'url'  => $this->wechat->oauth2->getAuthorizeUrl(route('wechat.home') . '#/user', true),
                    ],
                ],
            ],
        ];
        $response = $this->wechat->menu->create($button);
        
        if (isset($response['errcode']) && 0 !== $response['errcode']) {
            return $this->jsonResponse($response['errmsg'], -1);
        }
        
        return $this->jsonResponse('菜单保存成功');
    }

}
