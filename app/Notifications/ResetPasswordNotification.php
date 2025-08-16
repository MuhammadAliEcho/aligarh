<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        $resetUrl = URL::route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->view(
                'emails.reset-password',
                [
                    'token' => $this->token,
                    'user' => [
                        'email' => $notifiable->getEmailForPasswordReset()
                    ],
                    'url' => $resetUrl,
                ]
            );
    }
}
