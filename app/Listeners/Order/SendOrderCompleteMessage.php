<?php

namespace App\Listeners\Order;

use App\Events\Order\CompleteEvent as OrderCompleteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendSms;

// class SendOrderCompleteMessage implements ShouldQueue
class SendOrderCompleteMessage
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
     * @param  OrderCompleteEvent  $event
     * @return void
     */
    public function handle(OrderCompleteEvent $event)
    {
        $targets = $event->order->mobile;
        $content = '西交网管会温馨提示:' . 
            '您的网络报修已处理完毕，邀请您对我们的网管同学进行评价。感谢您的支持！'.
            '点击查看:[' . route('user.order.show', ['id' => $event->order->id]) .']';
        $sender = 'system@ana';
        $ip = \Request::capture()->ip();

        dispatch(new SendSms($targets, $content, $sender, $ip));
    }
}
