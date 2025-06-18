<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\ListingCreateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ListingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $listing;
    public $user;
    public function __construct($listing, $user)
    {
        $this->listing = $listing;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new ListingCreateMail($this->listing));
    }
}
