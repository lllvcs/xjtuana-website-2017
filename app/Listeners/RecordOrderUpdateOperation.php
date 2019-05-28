<?php

namespace App\Listeners;

use App\Events\Order\UpdateEvent as OrderUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\RecordOperation;

// class RecordOrderUpdateOperation implements ShouldQueue
class RecordOrderUpdateOperation
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
     * @param  OrderUpdateEvent  $event
     * @return void
     */
    public function handle(OrderUpdateEvent $event)
    {
        $order = $event->order;
        $user = $event->user;
        
        $user_id = $user->id;
        $content = '[订单编辑] - 用户[' . $user->profile->name . '](' . $user->netid . ')对订单[' . $order->id . ']进行了编辑操作。';

        dispatch(new RecordOperation($user_id, $content));
    }
}
