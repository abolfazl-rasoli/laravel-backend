<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Main\App\Helper\Helper;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Main\User\Model\User;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;

    public function test_user_edit_invalid_data()
    {
        $route = route('user.edit');
        $this->withOutLogin($route);
        $this->autoLoginUser();

        $lenPass = env("MIN_LENGTH_PASSWORD_FOR_REGISTER");

        $pass = Str::random($lenPass - 1);

        $data = ["name" => "ahmed", "password" => $pass, "password_confirmation" => $pass];
        $response = $this->putCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ["password"]]));

    }

    public function test_user_edit_valid_data_and_login_after_that()
    {
        $route = route('user.edit');
        $this->withOutLogin($route);
        $this->autoLoginUser();

        $lenPass = env("MIN_LENGTH_PASSWORD_FOR_REGISTER");

        $pass = Str::random($lenPass);

        $data = ["name" => "ahmed", "password" => $pass, "password_confirmation" => $pass];
        $response = $this->putCustom($route, $data);

        $fillable = $this->fillable(User::class);
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));

        $currentUser = $this->user($this->autoId);
        $this->assertEquals($currentUser->name, $data['name']);

        $response = $this->loginUser($currentUser->email, $data['password']);
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());

    }

    public function test_username_edit_invalid_data()
    {
        $route = route('user.edit.username');
        $this->withOutLogin($route);
        $this->autoLoginUser();

        $data = ["username" => "925a1994@"];
        $response = $this->putCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ["username"]]));

        $data = ["username" => "925a1994@gmail.com"];
        $response = $this->putCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ["username"]]));


        $data = ["username" => "0919526417"];
        $response = $this->putCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ["username"]]));

    }

    public function test_username_edit_put_cache()
    {
        $route = route('user.edit.username');
        $this->withOutLogin($route);
        $this->autoLoginUser();

        $data = ["username" => $this->faker->email];
        $response = $this->putCustom($route, $data);

        $response->assertStatus(201)->assertJsonStructure($this->jsonCallback(['message' => ["0", "ex"]]));

        $user = $this->user($this->autoId);

        $cache = Cache::get('cacheChangeEmail' . $user->id);
        $this->assertArrayHasKey('username', $cache);
        $this->assertArrayHasKey('token', $cache);
        $this->assertArrayHasKey('ct', $cache);

    }


    public function test_username_edit_tow_request_before_expire_token_in_cache()
    {

        $route = route('user.edit.username');
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $data = ["username" => $this->faker->email];
        $this->putCustom($route, $data);
        $response = $response = $this->putCustom($route, $data);

        $response->assertStatus(406)->assertJsonStructure($this->jsonCallback(['message' => ['0', 'ex']]));

        $this->assertGreaterThanOrEqual($response->original['message']['ex']
            , env('SECOND_TIME_EXPIRE_VERIFICATION_REGISTER_CODE'));

    }

    public function test_username_edit_and_verify()
    {
        $this->registerAndVerify();
    }

    public function test_username_edit_and_verify_by_invalid_token()
    {
        $route = route('user.edit.username.verify');
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $this->sendUsernameValid();

        //verify
        $response = $this->putCustom($route, ['token' => 12345]);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['token']]));

        $response = $this->putCustom($route, ['token' => Helper::makeToken()]);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }


    public function test_username_edit_and_verify_by_expire_token()
    {
        $route = route('user.edit.username.verify');
        $this->withOutLogin($route);
        $this->autoLoginUser();

        $this->sendUsernameValid();

        //verify
        $user = $this->user($this->autoId);
        $cache = Cache::get('cacheChangeEmail' . $user->id);
        Cache::forget('cacheChangeEmail' . $user->id);

        $response = $this->putCustom($route, ['token' => $cache['token']]);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }

    public function test_change_username_after_verify()
    {
        $this->autoLoginUser();

        $email = $this->faker()->email;
        $this->sendUsernameValid($email);

        $user = $this->user($this->autoId);

        $this->assertNotEquals($user->email, $email);

    }

    public function test_user_edit_avatar_with_invalid_data()
    {
        $route = route('user.edit.avatar');
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $data = ['avatar'=> UploadedFile::fake()->image('test2.zip')];
        $response = $this->postCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure(
            $this->jsonCallback(['message' => ['avatar']]));

        $data = ['avatar'=> null];
        $response = $this->postCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure(
            $this->jsonCallback(['message' => ['avatar']]));

    }

    public function test_user_edit_avatar_with_valid_data()
    {
        $route = route('user.edit.avatar');
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $data = ['avatar'=> UploadedFile::fake()->image('test2.png')];
        $response = $this->postCustom($route, $data);

        $fillable = $this->fillable(User::class);
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));

    }

    public function registerAndVerify()
    {
        $route = route('user.edit.username.verify');

        $this->autoLoginUser();

        $this->sendUsernameValid();

        //verify
        $user = $this->user($this->autoId);
        $cache = Cache::get('cacheChangeEmail' . $user->id);

        $response = $this->putCustom($route,  ['token' => $cache['token']]);

        $fillable = $this->fillable(User::class);
        return $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));
    }

    public function sendUsernameValid($email = null)
    {
        $route = route('user.edit.username');

        $data = ["username" => $email ?? $this->faker->email];
        $response = $this->putCustom($route, $data);

        $response->assertStatus(201)->assertJsonStructure($this->jsonCallback(['message' => ['0', 'ex']]));
        return $response;
    }

    public function withOutLogin(string $route)
    {
        $response = $this->postCustom($route, []);

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }

}
