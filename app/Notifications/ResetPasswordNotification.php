<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;

        $this->connection = 'database';
        $this->queue = 'default';
        $this->delay = now()->addSeconds(0);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your password reset OTP is: ' . $this->otp)
                    ->line('This OTP will expire in 10 minutes.')
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
