<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Meteorlxy\LaravelWechat\Foundation\WechatComponent;

class WechatController extends Controller
{
    use WechatComponent;

    public function getNetidByOpenid(Request $request) {
        if (!$request->has('openid') || !$request->has('appid')) {
            return $this->jsonResponse('Invalid request', -1);
        }

        $appid = $request->input('appid');

        if ($appid !== $this->wechat->config['appid']) {
            return $this->jsonResponse('Invalid request', -1);
        }

        $openid = $request->input('openid');

        $user = User::whereHas('wechat_account', function ($query) use ($openid) {
            $query->where('openid', '=', $openid);
        })->first();

        if (!$user) {
            return $this->jsonResponse('Have not bound netid', 1);
        }

        return $this->jsonResponse('OK', 0, [
            'netid' => $user->netid,
        ]);
    }
}
