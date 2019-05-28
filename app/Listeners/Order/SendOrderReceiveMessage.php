<?php

namespace App\Listeners\Order;

use App\Events\Order\ReceiveEvent as OrderReceiveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendSms;

// class SendOrderReceiveMessage implements ShouldQueue
class SendOrderReceiveMessage
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
     * @param  OrderReceiveEvent  $event
     * @return void
     */
    public function handle(OrderReceiveEvent $event)
    {
        $targets = $event->order->mobile;
        $content = '西交网管会温馨提示:' . 
            '您的网络报修已由网管会[' . $event->order->member->department->name . '-' . $event->order->member->user->profile->name . ']接受处理。若您长时间没有接到网管同学主动联系，可前往报修单页面进行催促。'. 
            '点击查看:[' . route('user.order.show', ['id' => $event->order->id]) .']';
        $sender = 'system@ana';
        $ip = \Request::capture()->ip();

        dispatch(new SendSms($targets, $content, $sender, $ip));
    }
}
