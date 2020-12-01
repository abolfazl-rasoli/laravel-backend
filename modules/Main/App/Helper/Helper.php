<?php

namespace Main\App\Helper;


use Illuminate\Support\Facades\Mail;
use Main\User\Mail\RegisterVerificationMail;
use Main\User\Model\User;

class Helper
{

    public static function makeToken()
    {
        return \Illuminate\Support\Str::random(6);
    }

    public static function fieldEmailOrMobile($value)
    {

        foreach (User::$login as $auth => $regex){
            if (preg_match( $regex , $value))
                return $auth;
        }
        return null;
    }

    public static function getChannel($username){

        return User::$loginNotify[static::fieldEmailOrMobile($username)];

    }


}
