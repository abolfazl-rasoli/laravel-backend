<?php


namespace Main\Permission\Traits;

use Main\Permission\Model\Permission;

trait PermissionTraits
{

    private function viewPermission()
    {

        return Permission::query()->paginate(env('PAGINATE'));

    }

}
