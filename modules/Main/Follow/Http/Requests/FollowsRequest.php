<?php

namespace Main\Follow\Http\Requests;
use Main\App\Request\MainRequest;


class FollowsRequest extends MainRequest
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
