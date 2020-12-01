<?php

namespace Main\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use Main\App\Helper\MessagesResource;
use Main\App\Helper\TryCache;
use Main\Permission\Http\Requests\ViewPermissionRequest;
use Main\Permission\Http\Resources\PermissionResource;
use Main\Permission\Traits\PermissionTraits;

class PermissionController extends Controller
{
    use PermissionTraits;

    /**
     * show all or one permission
     * @param ViewPermissionRequest $request
     * @return MessagesResource
     */
    public function view(ViewPermissionRequest $request)
    {
        return TryCache::render(function () use ($request) {

            $result = $request->permission ?? $this->viewPermission();
            return new PermissionResource($result);

        });
    }


}
