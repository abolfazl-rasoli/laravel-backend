<?php

namespace Main\Role\Http\Requests;

use Main\App\Request\MainRequest;

class RoleRequest extends MainRequest
{

    public function getRule($rule)
    {
        $role = $this->role ? 'unique:roles,title,'.  $this->role->id: null;
        $rulesList =
        [

            'title' => ['required', 'string', 'max:191', $role],

            'title_trans' => 'nullable|string|max:191',

            // different key
            'title_unique' => 'required|string|max:191|unique:roles,title'

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
