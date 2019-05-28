<?php

namespace App\Listeners\Order;

use App\Events\Order\UrgeEvent as OrderUrgeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendSms;

// class SendOrderUrgeMessage implements ShouldQueue
class SendOrderUrgeMessage
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
    public function handle(OrderUrgeEvent $event)
    {
        $order = $event->order;
        $order->load('building.members.user.profile', 'building.department.managers.user.profile');
        $targets = [];
        foreach($order->building->members as $member) {
            array_push($targets, $member->user->profile->mobile);
        }
        foreach($order->building->department->managers as $manager) {
            array_push($targets, $manager->user->profile->mobile);
        }
        $targets = implode(',', $targets);
        $content = '西交网管会温馨提示:' . 
            ' [' . $event->order->building->campus->name . '] [' . $event->order->building->name . '-' . $event->order->room . ']' .
            '用户正在催单，请尽快处理！点击查看:[' . url('/dashboard/order', [ $event->order->id ]) .']';
        $sender = 'system@ana';
        $ip = \Request::capture()->ip();

        dispatch(new SendSms($targets, $content, $sender, $ip));
    }
}
