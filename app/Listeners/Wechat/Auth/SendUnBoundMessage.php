<?php

namespace App\Listeners\Wechat\Auth;

use App\Events\Wechat\Auth\UnBoundEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendSms;

// class SendUnBoundMessage implements ShouldQueue
class SendUnBoundMessage
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
     * @param  UnBoundEvent  $event
     * @return void
     */
    public function handle(UnBoundEvent $event)
    {
        $this->sendSms($event);
    }
    
    protected function sendSms($event) {
        $targets = $event->user->profile->mobile;
        $content = '西交网管会温馨提示: 您的NetID已成功解绑微信号。（若非本人操作，则您的微信密码/NetID密码可能泄露，请尽快修改密码，并通过我们的公众号恢复绑定）';
        $sender = 'system@ana';
        $ip = \Request::capture()->ip();
        dispatch(new SendSms($targets, $content, $sender, $ip));
    }
}
