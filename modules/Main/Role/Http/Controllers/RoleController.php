<?php

namespace Main\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\Role\Http\Requests\CreateRoleRequest;
use Main\Role\Http\Requests\DeleteRoleRequest;
use Main\Role\Http\Requests\EditRoleRequest;
use Main\Role\Http\Requests\ViewRoleRequest;
use Main\Role\Http\Resources\RoleResource;
use Main\Role\Traits\RoleTraits;

class RoleController extends Controller
{
    use RoleTraits;

    /**
     * view all roles
     * @param ViewRoleRequest $request
     * @return mixed
     */
    public function view(ViewRoleRequest $request)
    {
        return TryCache::render(function () use ($request) {
            $result =  $this->viewRole($request->role);
            return new RoleResource($result);

        });
    }

    /**
     * create a role roles
     * @param CreateRoleRequest $request
     * @return mixed
     */
    public function create(CreateRoleRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $role = $this->createRole($request->title);
            return new RoleResource($role);

        });
    }

    /**
     * edit a role roles
     * @param EditRoleRequest $request
     * @return mixed
     */
    public function edit(EditRoleRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $this->editRole($request->role, $request->title);
            return new RoleResource($request->role->fresh());

        });
    }


    /**
     * delete a role roles
     * @param DeleteRoleRequest $request
     * @return mixed
     */
    public function delete(DeleteRoleRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $role = $this->deleteRole($request->role);
            return new RoleResource([$role]);

        });
    }

}
