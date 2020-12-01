<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Main\App\Helper\Helper;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Main\User\Model\User;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;

    private function data()
    {

        $pass = Helper::makeToken();
        return [
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "mobile" => "09195725690",
            "avatar" => UploadedFile::fake()->image('test2.png'),
            "password" => $pass,
            "password_confirmation" => $pass,
        ];
    }

    public function test_create_user_valid_data()
    {
        $route = route('user.create');
        $this->withOutLogin($route);
        $data = $this->data();
        $this->autoLoginUser();

        $response = $this->postCustom($route, $data);

        $fillable = $this->fillable(User::class, ['role']);
        $response->assertStatus(201)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));

        $user = $this->endUser();

        $this->assertEquals($user->email, $data['email']);
        $this->assertEquals($user->mobile, $data['mobile']);
        $this->assertEquals($user->name, $data['name']);
    }

    public function test_create_user_invalid_name()
    {
        $route = route('user.create');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = $this->data();

        $data['name'] = $this->faker()->text(300);
        $response = $this->postCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['name']]));

    }

    public function test_create_user_invalid_username()
    {
        $route = route('user.create');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = $this->data();

        $user = $this->user($this->autoId);
        $data['email'] = $user->email;
        $response = $this->postCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['email']]));

        $data['email'] = "158tft@gma";
        $response = $this->postCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['email']]));

    }

    public function test_create_user_invalid_password()
    {
        $route = route('user.create');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = $this->data();

        $data['password'] = 456789;
        $response = $this->postCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['password']]));

        unset($data['password']);
        $response = $this->postCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['password']]));
    }

    public function test_create_user_invalid_avatar()
    {
        $route = route('user.create');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = $this->data();

        $data['avatar'] = UploadedFile::fake()->image('test2.zip');
        $response = $this->postCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['avatar']]));
    }


    public function withOutLogin(string $route)
    {
        $response = $this->postCustom($route, $this->data());

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }
}
