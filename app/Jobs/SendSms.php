<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\SmsRepository;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targets;

    protected $content;

    protected $sender;

    protected $ip;

    protected $sms_repo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targets, $content, $sender, $ip)
    {
        $this->targets = $targets;
        $this->content = $content;
        $this->sender = $sender;
        $this->ip = $ip;
        $this->sms_repo = new SmsRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sms_repo->add([
            'targets' => $this->targets,
            'content' => $this->content,
            'sender' => $this->sender,
            'ip' => $this->ip,
        ]);
    }
}
