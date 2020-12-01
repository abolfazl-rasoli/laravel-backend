<?php


namespace Main\User\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Main\App\Helper\MessagesResource;
use Main\User\Exceptions\LoginException;

trait LoginTraits
{

    public function loginPassport($username, $password)
    {
        $invalidLogin = trans('messages.invalidLogin');
        $errorInLogin = trans('messages.errorInLogin');

        $req = Request::create(route('auth.login.passport', [],false), 'post', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => $username ,
            'password' => $password,
        ]);
        $response = app()->handle($req);
        /** @var Response $response */
        if ($response->status() >= 400){

            if ($response->status() === 400) {
                throw new LoginException($invalidLogin, 400);
            }
            if ($response->status() === 401){
                throw new LoginException( $response->original['message'], 401);
            }
            throw new LoginException( $errorInLogin, 500);
        }
        $token = 'Bearer '. json_decode($response->content())->access_token;
        return new MessagesResource(['s' => '200', 'data' => $token]);
    }

}
