<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Main\User\Model\User;
use Tests\TestCase;

class UserGetAndLogoutTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;

    public function test_user_get_information()
    {
        $this->autoLoginUser();
        $response = $this->getCustom(route('user.me'));

        $fillable = $this->fillable(User::class);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));
    }

    public function test_user_without_login()
    {
        $response = $this->getCustom(route('user.me'));

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback())
            ->assertJsonFragment(['data' => []]);
    }

    public function test_user_logout_without_login()
    {
        $response = $this->getCustom(route('user.logout'));
        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback());
    }

    public function test_user_logout_after_login()
    {
        $this->autoLoginUser();

        $response = $this->getCustom(route('user.logout'));
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());
    }
}
