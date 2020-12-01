<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;
    private $userData = [
        "email" => "test@gmail.com",
        "password" => "123456"
    ];

    private $userDataMobile = [
        "mobile" => "09195721595",
        "password" => "123456"
    ];

    public function test_login_by_email_user_without_verify_email()
    {
        $this->cUser($this->userData);

        $response = $this->loginUser($this->userData['email'], $this->userData['password']);

        $response->assertStatus(400)->assertJsonStructure($this->jsonCallback());
    }

    public function test_login_by_mobile_user_without_verify_mobile()
    {
        $this->cUser($this->userDataMobile);

        $response = $this->loginUser($this->userDataMobile['mobile'], $this->userDataMobile['password']);

        $response->assertStatus(400)->assertJsonStructure($this->jsonCallback());
    }

    public function test_login_by_email_and_change_password_confirm()
    {
        $this->cUser($this->userData);

        $response = $this->loginUser($this->userData['email'], $this->userData['password']
            , "54878798");

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['password']]));
    }

    public function test_login_by_email_and_invalid_data()
    {
        $this->cUser($this->userData);

        $response = $this->loginUser($this->userData['email'] . '8', $this->userData['password']);

        $response->assertStatus(400)->assertJsonStructure($this->jsonCallback());
    }

    public function test_login_by_email()
    {
        $data = array_merge(['verified_at' => now()], $this->userData);
        $this->cUser($data);

        $response = $this->loginUser($this->userData['email'] , $this->userData['password']);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());
    }

    public function test_login_by_mobile()
    {
        $data = array_merge(['verified_at' => now()], $this->userDataMobile);
        $this->cUser($data);

        $response = $this->loginUser($this->userDataMobile['mobile'] , $this->userDataMobile['password']);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());
    }

    public function test_login_by_email_and_inactive_user()
    {
        $data = array_merge(['verified_at' => now(), 'deleted_at' => now()], $this->userDataMobile);
        $this->cUser($data);

        $response = $this->loginUser($this->userDataMobile['mobile'] , $this->userDataMobile['password']);

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback());
    }
}
