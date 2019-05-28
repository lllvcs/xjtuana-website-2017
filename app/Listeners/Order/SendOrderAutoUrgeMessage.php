<?php

namespace App\Listeners\Order;

use App\Events\Order\AutoUrgeEvent as OrderAutoUrgeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendSms;

// class SendOrderAutoUrgeMessage implements ShouldQueue
class SendOrderAutoUrgeMessage
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
     * @param  OrderCreateEvent  $event
     * @return void
     */
    public function handle(OrderAutoUrgeEvent $event)
    {
        $order = $event->order;
        $order->load('building.members.user.profile');
        $targets = [];
        foreach($order->building->members as $member) {
            array_push($targets, $member->user->profile->mobile);
        }
        $targets = implode(',', $targets);
        $content = '西交网管会温馨提示:' . 
            ' [' . $event->order->building->campus->name . '] [' . $event->order->building->name . '-' . $event->order->room . ']' .
            '系统自动提醒您接单啦！点击查看:[' . url('/dashboard/order', [ $event->order->id ]) . ']';
        $sender = 'system@ana';
        $ip = \Request::capture()->ip();

        dispatch(new SendSms($targets, $content, $sender, $ip));
    }
}
