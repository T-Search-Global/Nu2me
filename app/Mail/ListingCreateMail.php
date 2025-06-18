<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ListingCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $listing;

    /**
     * Create a new message instance.
     */
    public function __construct($listing)
    {
        $this->listing = $listing;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Listing Has Been Created'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.listing_created', 
            with: [
                'listing' => $this->listing
            ]
        );
    }
}
