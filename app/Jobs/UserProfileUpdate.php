<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\UserRepository;

class UserProfileUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $netid;

    protected $user_repo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($netid)
    {
        $this->netid = $netid;
        $this->user_repo = new UserRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user_repo->autoUpdateProfile($this->netid, true);
    }
}
