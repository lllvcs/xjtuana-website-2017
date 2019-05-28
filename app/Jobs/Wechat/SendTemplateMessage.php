<?php

namespace App\Jobs\Wechat;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTemplateMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $template;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $template)
    {
        $this->wechat = resolve('wechat');
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->wechat->template->send($this->template);
        
        if (isset($response['errcode']) && 0 !== $response['errcode']) {
            
        }
    }
}
