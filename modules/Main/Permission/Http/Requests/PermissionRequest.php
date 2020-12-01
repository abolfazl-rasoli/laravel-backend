<?php

namespace Main\Permission\Http\Requests;

use Main\App\Request\MainRequest;

class PermissionRequest extends MainRequest
{

    public function getRule($rule)
    {
        $role = $this->permission ? 'unique:permissions,title,'.  $this->permission->id: null;
        $rulesList =
        [

            'title' => ['required', 'string', 'max:191', $role],

            // different key
            'title_unique' => 'required|string|max:191|unique:permissions,title'

        ];

        if (isset($rulesList[$rule]))
            return $rulesList[$rule];
        return '';
    }

    public function getRules($rules = [], $merge = []){
        $result = [];
        foreach ($rules as $rule){
            $result[$rule] = $this->getRule($rule);
        }
        return array_merge($result , $merge);
    }

}
