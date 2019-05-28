<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\API\Tool\SmsRequest;
use App\Http\Requests\API\Tool\UserInfoRequest;
use App\Http\Requests\API\Tool\UserPhotoRequest;
use App\Http\Requests\API\Tool\NetworkLogRequest;
use App\Http\Controllers\Controller;
use App\Jobs\SendSms;
use Auth;
use WsUserInfo;
use WsUserPhoto;
use ApiNetworkLog;

class ToolController extends Controller
{
    public function sms(SmsRequest $request) {
        
        $targets = $request->targets;
        $content = $request->content;
        $sender = $request->user()->netid;
        $ip = $request->ip();
        
        dispatch(new SendSms($targets, $content, $sender, $ip));
        
        return $this->jsonResponse('短信已发送');
    }
    
    public function userinfo(UserInfoRequest $request) {
        $data = [];
        switch($request->type) {
            case 'netid':
                $data = WsUserInfo::getByNetid($request->condition);
                break;
            case 'userno':
                $data = WsUserInfo::getByUserno($request->condition);
                break;
            case 'name':
                $data = WsUserInfo::getByName($request->condition);
                break;
            case 'mobile':
                $data = WsUserInfo::getByMobile($request->condition);
                break;
            default:
        }

        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function userphoto(UserPhotoRequest $request) {
        $data = WsUserPhoto::getByUserno($request->condition);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function networklog(NetworkLogRequest $request) {
        $data = [];
        switch($request->type) {
            case 'stu_pppoe':
                $data = ApiNetworkLog::getStuPppoeByUsername($request->username);
                break;
            case 'stu_wlan':
                $data = ApiNetworkLog::getStuWlanByUsername($request->username);
                break;
            case 'wenet_pppoe':
                $data = ApiNetworkLog::getWenetPppoeByUsername($request->username);
                break;
            default:
        }
        
        return $this->jsonResponse('OK', 0, $data);
    }
}
