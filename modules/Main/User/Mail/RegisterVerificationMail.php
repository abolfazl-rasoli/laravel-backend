<?php

namespace Main\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $token = null;

    /**
     * Create a new message instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject(trans('messages.subject_register_verification', ['appname' => env('APP_NAME')] ));
        return $this->view('User::verify', ['token' => $this->token]);
    }
}
