<?php

namespace Main\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Main\App\Helper\MessagesResource;
use Main\App\Helper\TryCache;
use Main\User\Http\Requests\LoginUserRequest;
use Main\User\Http\Requests\RegisterUserRequest;
use Main\User\Http\Requests\VerifyRegisterUserRequest;
use Main\User\Http\Requests\ViewRoleRequest;
use Main\User\Model\User;
use Main\User\Traits\CacheUserTraits;
use Main\User\Traits\LoginTraits;
use Main\User\Traits\RegisterTraits;

class AuthController extends Controller
{
    use LoginTraits, RegisterTraits , CacheUserTraits;

    /**
     * login a user with passport package
     * @param LoginUserRequest $request
     * @return mixed
     */
    public function login(LoginUserRequest $request)
    {
        return TryCache::render(function () use ($request) {
            return $this->loginPassport($request->username, $request->password);
        });
    }

    /**
     * register and send verification link in email user . and cache token verify
     * @param RegisterUserRequest $request
     * @return mixed
     */
    public function register(RegisterUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $user = $this->checkHasUser($request->username);

            if ($user) {
                return
                    $this->failOrSendResendVerificationCode($user, $request->username, $request->password);
            }

            return $this->registerNewUser($request->username, $request->password);

        }, true);
    }

    /**
     * verify user and login
     * @param VerifyRegisterUserRequest $request
     * @return MessagesResource
     */
    public function verify(VerifyRegisterUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $user = $this->checkHasUser($request->username);

            if($user){
                $cache = $this->getCache($user->id);
                if ($cache && $cache['token'] === $request->token ) {

                    DB::beginTransaction();
                    $user->verified_at = now();
                    $user->save();

                    $login = $this->loginPassport($request->username, $cache['ps']);
                    $this->forgetCache($user->id);

                    DB::commit();

                    return $login;
                }
            }


            return new MessagesResource([
                'message' => trans('messages.invalidVerify'), 's' => 402]);

        }, true);
    }

}
