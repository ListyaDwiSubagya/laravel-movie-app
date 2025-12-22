<?php

namespace App\Console\Commands;

use App\Jobs\CheckMemberhipStatus;
use Illuminate\Console\Command;

class CheckMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and deactivate expired memberships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CheckMemberhipStatus::dispatch();
        $this->info('Membership check job has been dispatched');
    }
}
