<?php


namespace Main\User\Traits;


use Illuminate\Support\Facades\DB;
use Main\App\Helper\Helper;
use Main\App\Helper\MessagesResource;
use Main\User\Model\User;
use Main\User\Notifications\VerifyNotification;

trait RegisterTraits
{
    private $cacheVerifyRegister = 'register_verify_code';

    /**
     * create user in database
     * @param $username
     * @param $password
     * @return mixed
     */
    public function addUser($username, $password)
    {
        return User::create([
            Helper::fieldEmailOrMobile($username) => $username,
            'password' => bcrypt($password),
        ]);
    }


    /**
     * check has user in database
     * @param $username
     * @return mixed
     */
    public function checkHasUser($username)
    {
        return User::firstWhere(Helper::fieldEmailOrMobile($username), $username);
    }

    /**
     * if user verified account fail response . else if resend notify from user
     * @param $user
     * @param $username
     * @param $password
     * @return MessagesResource
     */
    public function failOrSendResendVerificationCode($user, $username, $password)
    {
        $time = now()->addSeconds(env('SECOND_TIME_EXPIRE_VERIFICATION_REGISTER_CODE'));

        if ($user->verified_at) {
            return new MessagesResource([
                'message' => [trans('messages.alreadyVerified')],
                's' => 400
            ]);
        }

        $hasCache = $this->getCache($user->id);

        if ($hasCache) {
            return $this->notExpire($hasCache);
        }

        $token = Helper::makeToken();

        DB::beginTransaction();
        $user->password = $password;
        $user->save;

        $this->put($this->cacheVerifyRegister . $user->id,
            ['token' => $token, 'ct' => $time, 'ps' => $password], $time);

        $user->notify(new VerifyNotification( $token, $username));

        DB::commit();

        return $this->returnMessage(trans('messages.register'), $time);
    }

    /**
     * register new user with cache token and send notify
     * @param $username
     * @param $password
     * @return MessagesResource
     */
    public function registerNewUser($username, $password)
    {
        DB::beginTransaction();

        $token = Helper::makeToken();
        $time = now()->addSeconds(env('SECOND_TIME_EXPIRE_VERIFICATION_REGISTER_CODE'));

        $user = $this->addUser($username, $password);
        $this->put($this->cacheVerifyRegister . $user->id,
            ['token' => $token, 'ct' => $time, 'ps' => $password], $time);

        $user->notify(new VerifyNotification( $token, $username));

        DB::commit();
        return $this->returnMessage(trans('messages.register'), $time);
    }


    public function getCache($id)
    {
        return $this->get($this->cacheVerifyRegister . $id);
    }

    public function forgetCache($id)
    {
        $this->forget($this->cacheVerifyRegister . $id);
    }
}
