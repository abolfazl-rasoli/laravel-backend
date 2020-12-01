<?php

namespace Main\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Main\App\Helper\MessagesResource;
use Main\App\Helper\TryCache;
use Main\Role\Model\Role;
use Main\User\Http\Requests\CreateUserRequest;
use Main\User\Http\Requests\DeleteUserRequest;
use Main\User\Http\Requests\EditAvatarUserRequest;
use Main\User\Http\Requests\EditUsernameUserRequest;
use Main\User\Http\Requests\EditUserRequest;
use Main\User\Http\Requests\EditVerifyUsernameUserRequest;
use Main\User\Http\Requests\ForgetPasswordUserRequest;
use Main\User\Http\Requests\LogoutUserRequest;
use Main\User\Http\Requests\MeUserRequest;
use Main\User\Http\Requests\SearchUserRequest;
use Main\User\Http\Requests\StatusUserRequest;
use Main\User\Http\Resources\UserResource;
use Main\User\Model\User;
use Main\User\Traits\CacheUserTraits;
use Main\User\Traits\CreateUserTraits;
use Main\User\Traits\EditUserTraits;
use Main\User\Traits\ForgetPasswordTraits;
use Main\User\Traits\SearchUserTraits;

class UserController extends Controller
{

    use EditUserTraits;
    use CacheUserTraits;
    use CreateUserTraits;
    use SearchUserTraits;
    use ForgetPasswordTraits;

    /**
     * This way the user can get the information
     * @param MeUserRequest $request
     * @return MessagesResource
     */
    public function me(MeUserRequest $request)
    {
        return TryCache::render(function () {
            if($user = auth('api')->user()){
                return (new UserResource($user->with( ['getRole', 'getRole.permission'])->find($user->id)));
            }
            return new UserResource([]);
        });
    }

    /**
     * logout user
     * @param LogoutUserRequest $request
     * @return MessagesResource
     */
    public function logout(LogoutUserRequest $request)
    {
        return TryCache::render(function () {

            auth()->user()->token()->revoke();

            return new MessagesResource(
                ['s' => 200, 'message' => trans("messages.logout")]);
        });
    }

    /**
     * edit name and password user
     * @param EditUserRequest $request
     * @return MessagesResource
     */
    public function edit(EditUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $this->setUser($request);

            DB::beginTransaction();
            $this->saveName($request->name);
            $this->savePassword($request->password);
            $request->user_query && $this->saveRole($request->role);
            DB::commit();

            return new UserResource($this->user);
        }, true);
    }

    /**
     * edit username(email, mobile) user
     * @param EditUsernameUserRequest $request
     * @return MessagesResource
     */
    public function usernameEdit(EditUsernameUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $this->setUser($request);
            if ($request->user_query) {

                DB::beginTransaction();
                $this->saveUsername($request->username);
                DB::commit();

                return new UserResource($this->user);
            }

            return $this->changeUsername($request->username);

        }, true);
    }

    /**
     * verify new  username(email , mobile)  user
     * @param EditVerifyUsernameUserRequest $request
     * @return MessagesResource
     */
    public function usernameVerify(EditVerifyUsernameUserRequest $request)
    {
        return TryCache::render(function () use ($request) {
            DB::beginTransaction();

            $this->setUser($request);
            $verify = $this->verifyUsername($request->token);
            DB::commit();
            return $verify;

        }, true);
    }

    /**
     * upload avatar image
     * @param EditAvatarUserRequest $request
     * @return MessagesResource
     */
    public function editAvatar(EditAvatarUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $this->setUser($request);
            return $this->saveAvatar($request->avatar);

        });
    }

    /**
     * create user and verify
     * @param CreateUserRequest $request
     * @return MessagesResource
     */
    public function create(CreateUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            DB::beginTransaction();

            $this->createUserAndValidate($request->except('avatar'));
            $this->uploadAvatar($request->avatar);

            DB::commit();

            return new UserResource($this->user);
        }, true);

    }

    /**
     * active or inactive a user
     * @param StatusUserRequest $request
     * @return MessagesResource
     */
    public function status(StatusUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $user = User::withTrashed()->find($request->user_id);

            if ($user->deleted_at) {
                $user->restore();
                return new MessagesResource(
                    ['s' => '200', 'message' => trans('messages.activeUser')]);
            }

            $user->delete();
            return new MessagesResource(
                ['s' => '200', 'message' => trans("messages.inactiveUser")]);
        });
    }

    /**
     * delete a user
     * @param DeleteUserRequest $request
     * @return MessagesResource
     */
    public function delete(DeleteUserRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $user = User::withTrashed()->find($request->user_id);

            $user->forceDelete();
            return new MessagesResource(
                ['s' => '200', 'message' => trans("messages.deleteUser")]);
        });
    }

    /**
     * search user with created_at verified_at and name , email , ...
     * @param SearchUserRequest $request
     * @return MessagesResource
     */
    public function search(SearchUserRequest $request)
    {
        return TryCache::render(function () use($request){
            $this->request = $request;
            return $this->startProcess();
        });
    }

    /**
     * @param ForgetPasswordUserRequest $request
     * @return MessagesResource
     */
    public function forgetPassword(ForgetPasswordUserRequest $request)
    {
        return TryCache::render(function () use($request){
            return $this->sendNewPassword($request);
        });
    }

}
