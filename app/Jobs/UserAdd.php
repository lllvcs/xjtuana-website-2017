<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\UserRepository;

class UserAdd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $netid;

    protected $userRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($netid)
    {
        $this->netid = $netid;
        $this->userRepo = new UserRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->userRepo->add($this->netid);
    }
}
