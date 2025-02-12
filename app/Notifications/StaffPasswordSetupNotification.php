<?php

namespace App\Notifications;

use App\Classes\ApiResponseClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffPasswordSetupNotification extends Notification
{

    use Queueable;

    protected $token;

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
            ->subject('Set Your Password')
            ->line('Your account has been created. Please set your password by clicking the button below.')
            ->action('Set Password', url(config('app.url') . '/reset-password?token=' . $this->token))
            ->line('If you did not request this, no further action is needed.');
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.mail_failed'), 500);
        }
    }
}
