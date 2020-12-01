<?php


namespace Main\User\Traits;


use Illuminate\Support\Facades\DB;
use Main\App\Helper\Helper;
use Main\App\Helper\MessagesResource;
use Main\User\Model\User;
use Main\User\Notifications\ForgetPasswordNotification;

trait ForgetPasswordTraits
{


    private $forgetPassword = 'forgetPassword' ;

    private function sendNewPassword($request){

        $user = User::firstWhere(Helper::fieldEmailOrMobile($request->username), $request->username);

        $token = Helper::makeToken();
        $time = now()->addSeconds(env('SECOND_TIME_EXPIRE_FORGET_PASSWORD_EMAIL_MOBILE_CODE'));

        $hasCache = $this->get($this->forgetPassword . $user->id);



        if ($hasCache) {
           return $this->notExpire($hasCache);
        }

        $this->put($this->forgetPassword . $user->id, ['ct' => $time, 'token' => $token], $time);
        $this->put( $token, [], $time);


        $user->notify(new ForgetPasswordNotification( $token, $request->username));

        $tfp = env("SECOND_TIME_EXPIRE_FORGET_PASSWORD_EMAIL_MOBILE_CODE");


        return new MessagesResource([
            'message' =>[
                trans('messages.tempPassword',[ 'tfp' => $tfp]), 'ex' =>
                    now()->diffInSeconds($time)
            ], 's' => 201]);
    }

}
