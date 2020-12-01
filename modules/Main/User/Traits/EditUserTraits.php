<?php


namespace Main\User\Traits;


use Main\App\Helper\Helper;
use Main\App\Helper\MessagesResource;
use Main\Uploader\Uploader;
use Main\User\Http\Resources\UserResource;
use Main\User\Notifications\VerifyNotification;

trait EditUserTraits
{

    private $user;
    private $cacheChangeEmail = 'cacheChangeEmail';

    public function setUser($request)
    {
            $this->user = $request->user_query ?? auth()->user();
    }

    private function saveName($name)
    {
        if ($name) return $this->user->name = $name;
        return $name;
    }

    private function savePassword($password)
    {
        if ($password) return $this->user->update(['password' => bcrypt($password)]);
        return $password;
    }

    private function saveRole($role)
    {
        return $this->user->update(['role' => $role]);
    }

    private function saveUsername($username){
        return $this->user->update([Helper::fieldEmailOrMobile($username) => $username]);
    }

    private function changeUsername($username)
    {
        $token = Helper::makeToken();
        $time = now()->addSeconds(env('SECOND_TIME_EXPIRE_CHANGE_EMAIL_MOBILE_CODE'));

        $hasCache = $this->get($this->cacheChangeEmail . $this->user->id);

        if ($hasCache) {
            return $this->notExpire($hasCache);
        }

        $this->put($this->cacheChangeEmail . $this->user->id,
            ['token' => $token, 'ct' =>$time , 'username' => $username], $time);

        $newUser = $this->user;
        $newUser[Helper::fieldEmailOrMobile($username)] = $username;
        $newUser->notify(new VerifyNotification( $token, $username));

        return $this->returnMessage(trans('messages.register'), $time);
    }

    private function verifyUsername($token)
    {

        $hasCache = $this->get($this->cacheChangeEmail . $this->user->id);

        if ($hasCache && $hasCache['token'] === $token) {

            $this->saveUsername($hasCache['username']);

            $this->forget($this->cacheChangeEmail . $this->user->id);

            return new UserResource($this->user);
        }

        return new MessagesResource([
            'message' => trans('messages.invalidVerify'), 's' => 402]);
    }

    public function saveAvatar($avatar)
    {
        if($path = Uploader::upload($this->user->id, $avatar, 'avatar')) {
            $this->user->avatar &&
                Uploader::removeFile($this->user->getAttributes()['avatar'], 'avatar');
            $this->user->update(['avatar' => $path]);

            return new UserResource($this->user);
        }
    }

}
