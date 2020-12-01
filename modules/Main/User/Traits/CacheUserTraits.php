<?php


namespace Main\User\Traits;


use Illuminate\Support\Facades\Cache;
use Main\App\Helper\MessagesResource;

trait CacheUserTraits
{

    private function get($id){
        return Cache::get($id);
    }

    private function notExpire($cache){

        $tex = env("SECOND_TIME_EXPIRE_VERIFICATION_REGISTER_CODE");
        return new MessagesResource([
            'message' => [
                trans('messages.not_expire_register_verification', ['tex' => $tex]),
                'ex' => now()->diffInSeconds($cache['ct'])
            ],
            's' => 406
        ]);

    }

    private function put($id , $value, $time)
    {
        return Cache::put($id, $value, $time);
    }

    private function returnMessage($message, $time)
    {
        return new MessagesResource(['message' => [$message, 'ex' => now()->diffInSeconds($time)], 's' => 201]);
    }

    private function forget($id){
        Cache::forget( $id);
    }

}
