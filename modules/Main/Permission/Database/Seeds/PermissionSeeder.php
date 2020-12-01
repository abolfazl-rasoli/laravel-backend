<?php
namespace Main\Permission\Database\Seeds;

use Illuminate\Database\Seeder;
use Main\Permission\Model\Permission;
use Main\Role\Http\Requests\CreateRoleRequest;
use Main\Role\Http\Requests\DeleteRoleRequest;
use Main\Role\Http\Requests\EditRoleRequest;
use Main\Setting\Http\Controllers\SettingController;
use Main\Setting\Http\Requests\UpdateSettingRequest;
use Main\Uploader\Http\Requests\DeleteUploaderRequest;
use Main\Uploader\Http\Requests\UploadUploaderRequest;
use Main\Uploader\Http\Requests\ViewUploaderRequest;
use Main\User\Http\Requests\CreateUserRequest;
use Main\User\Http\Requests\DeleteUserRequest;
use Main\User\Http\Requests\EditAvatarUserRequest;
use Main\User\Http\Requests\EditUsernameUserRequest;
use Main\User\Http\Requests\EditUserRequest;
use Main\User\Http\Requests\SearchUserRequest;
use Main\User\Http\Requests\StatusUserRequest;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRole();
    }

    public function createRole(): void
    {
        Permission::create(['title' => 'edit users', 'class' => EditUserRequest::class]);
        Permission::create(['title' => 'create user', 'class' => CreateUserRequest::class]);
        Permission::create(['title' => 'change status user', 'class' => StatusUserRequest::class]);
        Permission::create(['title' => 'delete user', 'class' => DeleteUserRequest::class]);
        Permission::create(['title' => 'search user', 'class' => SearchUserRequest::class]);
        Permission::create(['title' => 'edit username user', 'class' => EditUsernameUserRequest::class]);
        Permission::create(['title' => 'edit avatar user', 'class' => EditAvatarUserRequest::class]);
        Permission::create(['title' => 'create role', 'class' => CreateRoleRequest::class]);
        Permission::create(['title' => 'edit role', 'class' => EditRoleRequest::class]);
        Permission::create(['title' => 'delete role', 'class' => DeleteRoleRequest::class]);
        Permission::create(['title' => 'view all env', 'class' => SettingController::class]);
        Permission::create(['title' => 'update env', 'class' => UpdateSettingRequest::class]);
        Permission::create(['title' => 'view Uploader files', 'class' => ViewUploaderRequest::class]);
        Permission::create(['title' => 'upload multi file', 'class' => UploadUploaderRequest::class]);
        Permission::create(['title' => 'delete multi file', 'class' => DeleteUploaderRequest::class]);
    }

}
