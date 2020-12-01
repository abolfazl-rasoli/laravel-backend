<?php

namespace Main\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Main\App\Helper\Helper;
use Main\User\Broadcasting\EmailChannel;
use Main\User\Mail\ForgetPasswordMail;
use Main\User\Mail\RegisterVerificationMail;

class ForgetPasswordNotification extends Notification
{
    use Queueable;
    private $user;
    private $token;
    private $username;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $username
     */
    public function __construct ($token, $username)
    {
        $this->token = $token;
        $this->username = $username;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [Helper::getChannel($this->username)];
    }

    public function toEmail($notifiable)
    {
        Mail::to($notifiable)->send(new ForgetPasswordMail($this->token));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
