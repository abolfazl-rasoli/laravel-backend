<?php

namespace Main\PermissionRole\Http\Controllers;
use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\PermissionRole\Http\Requests\AttachPermissionRoleRequest;
use Main\PermissionRole\Http\Requests\ViewPermissionRoleRequest;
use Main\PermissionRole\Http\Resources\PermissionRoleResource;

class PermissionRoleController extends Controller
{

    public function attach(AttachPermissionRoleRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $request->permission->role()->toggle([$request->role->id]);

            return new PermissionRoleResource($request->role->permission);

        }, true);
    }

    public function view(ViewPermissionRoleRequest $request)
    {
        return TryCache::render(function () use ($request) {

            return new PermissionRoleResource($request->role->permission);

        }, true);
    }

}
