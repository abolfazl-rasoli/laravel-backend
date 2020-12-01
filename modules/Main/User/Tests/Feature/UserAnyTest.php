<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Main\User\Model\User;
use Tests\TestCase;

class UserAnyTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;

    public function test_user_change_status_invalid_date()
    {
        $route = route('user.status');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $response = $this->postCustom($route, ['user_id' => 100]);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['user_id']]));

        $response = $this->postCustom($route, []);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['user_id']]));
    }

    public function test_user_change_status_valid_date()
    {
        $route = route('user.status');
        $this->withOutLogin($route);

        $id = 5;
        $response = $this->postCustom($route, ['user_id' => $id]);

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

        $this->autoLoginUser();

        $id = 5;
        $response = $this->postCustom($route, ['user_id' => $id]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

        $user = $this->user($id);
        $this->assertTrue((bool) $user->deleted_at);

        $response = $this->postCustom($route, ['user_id' => $id]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

        $user = $this->user($id);
        $this->assertFalse((bool) $user->deleted_at);
    }

    public function test_user_force_delete_valid_data()
    {
        $route = route('user.delete');
        $this->withOutLogin($route);

        $id = 5;
        $response = $this->postCustom($route, ['user_id' => $id]);
        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

        $this->autoLoginUser();
        $id = 5;
        $response = $this->postCustom($route, ['user_id' => $id]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

        $user = $this->user($id);
        $this->assertNull($user);

    }

    public function test_user_force_delete_invalid_data()
    {
        $route = route('user.delete');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $response = $this->postCustom($route, ['user_id' => 100]);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['user_id']]));

    }

    public function test_search_user_valid_data()
    {
        $route = route('user.search');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = ['search' => 'm', 'status' => 'active' , 'created_at' => '5'];
        $response = $this->postCustom($route, $data);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());

    }

    public function test_search_user_invalid_data()
    {
        $route = route('user.search');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = ['search' => Str::random(192), 'status' => "active p" ,
            'created_at' => "1000 p",'verified_at' => "1000 p"];
        $response = $this->postCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(
            ['message' => ['created_at', 'verified_at', 'search', 'status']]));

        $data = ['search' => random_int(10, 20), 'created_at' => "100/98",'verified_at' => "1000 p"];
        $response = $this->postCustom($route, $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(
            ['message' => ['created_at', 'verified_at', 'search']]));

    }

    public function test_search_all_user()
    {
        $route = route('user.search');
        $this->withOutLogin($route);

        $this->autoLoginUser();
        $data = [];
        $response = $this->postCustom($route, $data);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback())
            ->assertJsonFragment(['total' => User::count()]);

    }

    public function test_forget_password_invalid_data()
    {
        $data = ['username' => '4949498'];
        $response = $this->postCustom(route('user.forget.password'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['username']]));

        $data = [];
        $response = $this->postCustom(route('user.forget.password'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['username']]));

        $data = ['username', $this->faker->email];
        $response = $this->postCustom(route('user.forget.password'), $data);

        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['username']]));
    }

    public function test_forget_password_valid_data()
    {
        $currentUser =  $this->user(4);
        $this->forgetPassword($currentUser);

    }

    public function test_login_with_temp_password_in_forget_password_valid_data()
    {
        $currentUser =  $this->user(4);
        $this->forgetPassword($currentUser);


        $cache = Cache::get('forgetPassword'. $currentUser->id);
        $response = $this->loginUser($currentUser->email, $cache['token']);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());

    }

    public function test_login_just_one_with_temp_password_in_forget_password_valid_data()
    {
        $currentUser =  $this->user(4);
        $this->forgetPassword($currentUser);

        $cache = Cache::get('forgetPassword'. $currentUser->id);
        $response = $this->loginUser($currentUser->email, $cache['token']);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback());

        $response = $this->loginUser($currentUser->email, $cache['token']);
        $response->assertStatus(400)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

    }

    public function forgetPassword($currentUser)
    {
        $data = ['username' => $currentUser->email];
        $response = $this->postCustom(route('user.forget.password'), $data);

        $response->assertStatus(201)->assertJsonStructure($this->jsonCallback(['message' => ['0', 'ex']]));
    }

    public function withOutLogin(string $route)
    {
        $response = $this->postCustom($route, []);

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }

}
