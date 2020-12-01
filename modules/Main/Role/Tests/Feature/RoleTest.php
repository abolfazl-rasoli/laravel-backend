<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Main\App\Helper\Helper;
use Main\App\Tests\TraitsMethodsTest;
use Main\App\Tests\TraitsTest;
use Main\Role\Model\Role;
use Main\User\Model\User;
use Tests\TestCase;

class CreateRoleTest extends TestCase
{
    use TraitsTest, WithFaker, TraitsMethodsTest;

    public function test_create_role_valid_data()
    {
        $route = route('role.create');
        $this->withOutLogin($route);

        $data = ['title' => $this->faker->title];
        $this->autoLoginUser();

        $response = $this->postCustom($route, $data);

        $fillable = $this->fillable(Role::class);
        $response->assertStatus(201)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));

        $role = Role::all()->last();

        $this->assertEquals($role->title, $data['title']);
    }

    public function test_create_role_invalid_data()
    {
        $route = route('role.create');
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $data = [];
        $response = $this->postCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['title']]));

        $data = ['title' => 'super_admin'];
        $response = $this->postCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['title']]));

    }

    public function test_view_role_with_show_super_admin()
    {
        $route = route('role.view');
        $this->withOutLoginGetMethod($route);

        $this->autoLoginUser();

        $response = $this->getCustom($route);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(
            ['data']))->assertJsonFragment(['total' => Role::withTrashed()->count()]);

    }

    public function test_view_current_role_by_query()
    {
        $route = route('role.view', ['role' => '2']);
        $this->withOutLoginGetMethod($route);

        $this->autoLoginUser();

        $response = $this->getCustom($route);
        $fillable = $this->fillable(Role::class);
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));
    }

    public function test_edit_role_valid_date()
    {
        $route = route('role.edit', ['role' => 2]);
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $data = ['title' => $this->faker->title];
        $response = $this->putCustom($route, $data);
        $fillable = $this->fillable(Role::class);

        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => $fillable]));

        $role = Role::find(2);
        $this->assertEquals($role->title, $data['title']);
    }

    public function test_edit_role_invalid_date()
    {
        $route = route('role.edit', ['role' => 2]);
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $data = ['title' => 'super_admin'];
        $response = $this->putCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['title']]));

        $data = [];
        $response = $this->putCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['title']]));

        $data = ['title' => 4894];
        $response = $this->putCustom($route, $data);
        $response->assertStatus(402)->assertJsonStructure($this->jsonCallback(['message' => ['title']]));

    }

    public function test_delete_role_valid_data()
    {
        $route = route('role.delete', ['role' => 2]);
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $response = $this->deleteCustom($route);
        $response->assertStatus(200)->assertJsonStructure($this->jsonCallback(['data' => ['0']]));

        $role = Role::find(2);
        $this->assertNull($role);

    }

    public function test_delete_role_invalid_data()
    {
        $route = route('role.delete', ['role' => 3]);
        $this->withOutLogin($route);

        $this->autoLoginUser();

        $response = $this->deleteCustom($route);
        $response->assertStatus(404)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));


        $route = route('role.delete', ['role' => 1]);
        $response = $this->deleteCustom($route);
        $response->assertStatus(404)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));

    }

    public function withOutLogin(string $route)
    {
        $response = $this->postCustom($route, []);

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }

    public function withOutLoginGetMethod(string $route)
    {
        $response = $this->getCustom($route, []);

        $response->assertStatus(401)->assertJsonStructure($this->jsonCallback(['message' => ['0']]));
    }
}
