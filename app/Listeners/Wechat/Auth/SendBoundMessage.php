<?php

namespace App\Listeners\Wechat\Auth;

use App\Events\Wechat\Auth\BoundEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Wechat\SendTemplateMessage;
use App\Jobs\SendSms;

// class SendBoundMessage implements ShouldQueue
class SendBoundMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BoundEvent  $event
     * @return void
     */
    public function handle(BoundEvent $event)
    {
        $this->sendWechatTempalteMessage($event);
        $this->sendSms($event);
    }
    
    protected function sendWechatTempalteMessage($event) {
        $user = $event->user;
        $template = [
            'touser' => $user->wechat_account->openid,
            'template_id' => config('xjtuana_wechat.template.auth.bind'),
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
                    'value' => $user->wechat_account->created_at->toDateTimeString(),
                    'color' => '#173177',
                ],
                'remark' => [
                    'value' => "\n" . '成功绑定NetID，现在可以通过微信菜单使用校园服务功能。',
                    'color' => '#aaaaaa',
                ],
            ],
        ];
        dispatch(new SendTemplateMessage($template));
    }
    
    protected function sendSms($event) {
        $targets = $event->user->profile->mobile;
        $content = '西交网管会温馨提示: 您的NetID已成功绑定微信号。（若非本人操作，则您的微信密码/NetID密码可能泄露，请尽快修改密码，并通过我们的公众号进行解绑操作。）';
        $sender = 'system@ana';
        $ip = \Request::capture()->ip();
        dispatch(new SendSms($targets, $content, $sender, $ip));
        
    }
}
