<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;
    private $userData = [
        "username" => "test@gmail.com",
        "password" => "123456",
        "password_confirmation" => "123456"
    ];
    private $userDataToken = [
        "username" => "test@gmail.com",
        "token" => "123456"
    ];


    public function test_register_by_different_password_confirm()
    {
        $data = array_merge($this->userData, ['password_confirmation' => "557878"]);
        $response = $this->postCustom(route('auth.register'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['password']]));
    }

    public function test_register_by_invalid_username()
    {
        $data = array_merge($this->userData, ['username' => "4849g@gmail"]);
        $response = $this->postCustom(route('auth.register'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['username']]));

        $data = array_merge($this->userData, ['username' => "091954876"]);
        $response = $this->postCustom(route('auth.register'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['username']]));
    }

    public function test_register()
    {
        $response = $this->postCustom(route('auth.register'), $this->userData);

        $response->assertStatus(201)->assertJsonStructure($this->jsonCallback(['message' => ['0', 'ex']]));
    }

    public function test_register_after_exist_user_in_database()
    {
        $this->postCustom(route('auth.register'), $this->userData);

        $user = $this->endUser();

        $this->assertEquals($user->email, $this->userData['username']);
        $this->assertEquals($user->verified_at, null);
        $this->assertEquals($user->deleted_at, null);
    }

    public function test_register_cache()
    {
        $this->postCustom(route('auth.register'), $this->userData);

        $user = $this->endUser();
        $cache = Cache::get('register_verify_code' . $user->id);

        $this->assertArrayHasKey('ps', $cache);
        $this->assertArrayHasKey('token', $cache);
        $this->assertArrayHasKey('ct', $cache);
    }

    public function test_register_tow_request_before_expire_token_in_cache()
    {
        $this->postCustom(route('auth.register'), $this->userData);

        $response = $this->postCustom(route('auth.register'), $this->userData);

        $response->assertStatus(406)->assertJsonStructure($this->jsonCallback(['message' => ['0', 'ex']]));

        $this->assertGreaterThanOrEqual($response->original['message']['ex']
            , env('SECOND_TIME_EXPIRE_VERIFICATION_REGISTER_CODE'));
    }

    public function test_register_verify_invalid_token_and_username()
    {
        $this->postCustom(route('auth.register'), $this->userData);

        $data = array_merge($this->userDataToken , ['token' => 123]);
        $response = $this->postCustom(route('auth.verify'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['token']]));


        $data = array_merge($this->userDataToken , ['username' => "9251om"]);
        $response = $this->postCustom(route('auth.verify'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['username']]));

    }

    public function test_register_verify_cache_check_token()
    {
        $this->postCustom(route('auth.register'), $this->userData);

        $user = $this->endUser();
        $cache = Cache::get('register_verify_code' . $user->id);

        $data = array_merge($this->userDataToken , ['token' => $cache['token']]);
        $response = $this->postCustom(route('auth.verify'), $data );
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());

        // wrong token
        $data = array_merge($this->userDataToken , ['token' => '987987']);
        $response = $this->postCustom(route('auth.verify'), $data );
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

    }


}
