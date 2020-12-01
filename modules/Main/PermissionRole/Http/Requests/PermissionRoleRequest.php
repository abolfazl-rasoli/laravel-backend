<?php

namespace Main\PermissionRole\Http\Requests;
use Main\App\Request\MainRequest;


class PermissionRoleRequest extends MainRequest
{

    public function getRule($rule)
    {
        $rulesList =
            [

                // different key

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
