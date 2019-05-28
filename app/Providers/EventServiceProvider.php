<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Order\CreateEvent' => [
            'App\Listeners\Order\SendOrderCreateMessage',
        ],
        'App\Events\Order\UrgeEvent' => [
            'App\Listeners\Order\SendOrderUrgeMessage',
        ],
        'App\Events\Order\AutoUrgeEvent' => [
            'App\Listeners\Order\SendOrderAutoUrgeMessage',
        ],
        'App\Events\Order\ReceiveEvent' => [
            'App\Listeners\Order\SendOrderReceiveMessage',
        ],
        'App\Events\Order\ReportEvent' => [
            
        ],
        'App\Events\Order\CompleteEvent' => [
            'App\Listeners\Order\SendOrderCompleteMessage',
        ],
        'App\Events\Order\UpdateEvent' => [
            'App\Listeners\RecordOrderUpdateOperation',
        ],
        
        // Wechat
        'App\Events\Wechat\Auth\BoundEvent' => [
            'App\Listeners\Wechat\Auth\SendBoundMessage'
        ],
        'App\Events\Wechat\Auth\UnBoundEvent' => [
            'App\Listeners\Wechat\Auth\SendUnBoundMessage'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
