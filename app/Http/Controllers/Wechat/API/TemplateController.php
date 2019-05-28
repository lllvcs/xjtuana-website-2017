<?php

namespace App\Http\Controllers\Wechat\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Requests\API\Wechat\Menu\ViewRequest;
// use App\Http\Requests\API\Wechat\Menu\UpdateRequest;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;

class TemplateController extends Controller
{
    use WechatComponent;
    
    public function index(Request $request) {
        $data = $this->wechat->template->get();
        return $this->jsonResponse('OK', 0, $data);
    }

    public function send(Request $request) {
        $user = $request->user();
        $template = [
            'touser' => $user->wechat_account->openid,
            'template_id' => 'sXPXR7VVT3_ZgPXY5m9qpTLIbg_TSGm4_PZttsLyzXM',
            'url' => '',
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => '微信绑定成功' . "\n",
                    'color' => '#aaaaaa',
                ],
                'keyword1' => [
                    'value' => $user->netid,
                    'color' => '#173177',
                ],
                'keyword2' => [
                    'value' => $user->wechat_account->created_at,
                    'color' => '#173177',
                ],
                'remark' => [
                    'value' => "\n" . '成功绑定NetID，现在可以通过微信菜单使用校园服务功能。',
                    'color' => '#aaaaaa',
                ],
            ],
        ];
        $response = $this->wechat->template->send($template);
        
        if (isset($response['errcode']) && 0 !== $response['errcode']) {
            return $this->jsonResponse($response['errmsg'], -1);
        }
        
        return $this->jsonResponse('模板消息发送成功');
    }

}
