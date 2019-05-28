<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\OperationHistoryRepository;

class RecordOperation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;

    protected $content;

    protected $operation_history_repo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $content)
    {
        $this->user_id = $user_id;
        $this->content = $content;
        $this->operation_history_repo = new OperationHistoryRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->operation_history_repo->add([
            'user_id' => $this->user_id,
            'content' => $this->content,
        ]);
    }
}
