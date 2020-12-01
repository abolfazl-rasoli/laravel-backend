<?php

namespace Main\User\Broadcasting;

use Illuminate\Notifications\Notification;
use Main\User\Model\User;

class EmailChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notification->toEmail($notifiable);
    }
}
