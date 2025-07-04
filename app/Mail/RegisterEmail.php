<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $last_name;

    /**
     * Create a new message instance.
     */
    public function __construct($first_name, $last_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }



    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Register Email');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.register',
            with: [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
            ],
        );
    }
}
