<?php

namespace App\Jobs;

use App\Mail\RegisterEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {

        $this->user = $user;
     }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->user->email)->send(new RegisterEmail($this->user->first_name, $this->user->last_name));
    }
}
