<?php


namespace Main\Role\Traits;


use Main\Language\Traits\TranslateTraits;
use Main\Role\Model\Role;

trait RoleTraits
{

    use TranslateTraits;

    private function viewRole($role = null)
    {
        if ($role)
            return $role->with(['permission'])->first();

        return Role::withTrashed()->with(['permission'])->paginate(env('PAGINATE'));

    }

    private function createRole($title)
    {

        return Role::query()->create(['title' => $title]);

    }

    private function editRole($role, $title)
    {

        return $role->update(['title' => $title]);

    }

    private function deleteRole($role)
    {

        return $role->forceDelete();

    }

}
