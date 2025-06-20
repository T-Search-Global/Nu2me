<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $id;

    public $name;
    public $email;

    public function __construct($id, $name,$email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Otp Send Successfully',
        );
    }

    public function content(): Content
{
    $otpRecord = DB::table('password_reset_tokens')->where('email', $this->email)->first();
    $code = rand(1000, 9999); // 4 digit random OTP

    if ($otpRecord) {
        // Update existing record
        DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->update([
                'token' => bcrypt($code), // better to encrypt OTP
                'created_at' => Carbon::now(),
            ]);
    } else {
        // Insert new record
        DB::table('password_reset_tokens')->insert([
            'email' => $this->email,
            'token' => bcrypt($code),
            'created_at' => Carbon::now(),
        ]);
    }

    return new Content(
        markdown: 'emails.otp',
        with: [
            'username' => $this->name,
            'otp' => $code,
        ],
    );
}


    public function attachments(): array
    {
        return [];
    }
}
