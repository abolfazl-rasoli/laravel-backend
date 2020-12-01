<?php

namespace Main\Setting\Http\Requests;

use Main\App\Request\MainRequest;

class SettingRequest extends MainRequest
{

    public function getRule($rule)
    {
        $rulesList =
        [

            'settings' => "nullable|array",
            'settings.*' => 'nullable|string',
            'env' => collect(array_keys($_ENV))->flatMap(function ($item) {
                return [$item => 'nullable|string'];
            })->toArray()

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
