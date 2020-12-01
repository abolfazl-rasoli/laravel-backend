<?php


namespace Main\User\Traits;


use Main\Uploader\Uploader;
use Main\User\Http\Resources\UserResource;
use Main\User\Model\User;

trait CreateUserTraits
{

    private $user ;
    public function createUserAndValidate($data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['verified_at'] = now();

        $this->user = User::create($data);
        return $this->user;
    }

    public function uploadAvatar($avatar)
    {
        if($avatar && $path = Uploader::upload($this->user->id, $avatar, 'avatar')) {

            $this->user->update(['avatar' => $path]);

            return new UserResource($this->user);
        }
    }
}
