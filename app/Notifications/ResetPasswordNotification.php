<?php

namespace App\Notifications;

use App\Classes\ApiResponseClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        try {
            return (new MailMessage)
                ->subject('Reset Your Password')
                ->line('You requested a password reset. Click the button below to reset your password.')
                ->action('Reset Password', url(config('app.url') . '/reset-password?token=' . $this->token))
                ->line('If you did not request a password reset, please ignore this email.');
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.mail_failed'), 500);
        }
    }
}
