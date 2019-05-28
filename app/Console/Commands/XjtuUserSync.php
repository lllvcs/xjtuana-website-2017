<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UserAdd;
use App\Models\XjtunicUser;

class XjtuUserSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xjtu-user:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all users\' profile';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = XjtunicUser::doesntHave('xjtuana_user')->get();
        $count = $users->count();

        $this->info('Totally ' . $count . ' new user(s)');
        $this->info('Dispatching jobs into task queue...');
        $this->info('');

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($users as $user) {
            dispatch(new UserAdd($user->userid));
            $bar->advance();
        }
        $bar->finish();

        $this->info('');
        $this->info('Done. Jobs in progress.');
    }
}
