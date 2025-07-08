<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Console\Command;

class ExpireEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();
        $events = Event::get();
        
        foreach($events as $event){
            if($event->created_at->addDays(30) <= $today){
                $event->update([
                    'approve' => false,
                    'expired_at' => $today,
                ]);
            }
        }

        $this->info('Events expired today or earlier have been reset.');
    }
}
